"""Shared, offline FAQ parsing for review PDFs, local schema and verification."""
from dataclasses import dataclass
from html import unescape
from pathlib import Path
import re

ROOT = Path(__file__).resolve().parents[1]
REVIEW_DATE = '2026-09-09'
QUESTION = re.compile(r'^\*\*Q\. (.+?)\*\*\s*$', re.M)
COMMENT = re.compile(r'<!--.*?-->', re.S)

# Owner-approved exception: exact questions, providers and section only.
IMMIGRATION_APPROVAL = 'IMM-US-UK-2026-09-09'
IMMIGRATION_SECTION = 'Immigration & Visa Eligibility'
IMMIGRATION_QUESTIONS = {
    'rushford-business-school': 'Does completing a Rushford Business School programme make me eligible to migrate to the US or UK?',
    'girne-american-university': 'Does completing a Girne American University programme make me eligible to migrate to the US or UK?',
}
IMMIGRATION_DISCLAIMER = (
    'Immigration and residency decisions rest solely with the relevant authorities. '
    'This information is general guidance, not immigration advice.'
)
IMMIGRATION_REPORT_PDF = 'Maverick-GAU-RBS-Immigration-FAQ-Report.pdf'
IMMIGRATION_REPORT_MD = 'gau-rbs-immigration-report-2026-09-09.md'
IMMIGRATION_EVIDENCE = ROOT / 'reports/immigration-evidence-2026-09-09.json'


def is_immigration_faq(slug, faq):
    return (faq.category == IMMIGRATION_SECTION
            and faq.question == IMMIGRATION_QUESTIONS.get(slug))


@dataclass(frozen=True)
class Provider:
    slug: str
    name: str
    coverage: str
    listing_categories: tuple[str, ...]

    @property
    def path(self):
        return ROOT / 'approved' / f'{self.slug}.md'

    @property
    def programme_count(self):
        listing = programme_listing()
        return sum(len(listing[key]) for key in self.listing_categories)


PROVIDERS = (
    Provider('rushford-business-school', 'Rushford Business School (RBS)',
             'BBA | MBA | MSc | Doctoral', ('1.1', '1.2', '1.3', '1.4')),
    Provider('girne-american-university', 'Girne American University (GAU)',
             "BSc | MBA | EMBA | Thesis-based master's | PhD", ('2.1', '2.2', '2.3', '2.4', '2.5')),
    Provider('university-west-scotland', 'University of the West of Scotland (UWS)',
             'Global Business (exact award subject to confirmation)', ('3.1',)),
    Provider('university-creative-arts', 'University for the Creative Arts (UCA)',
             'Global MBA (linked award pathway)', ('4.1',)),
    Provider('university-wolverhampton', 'University of Wolverhampton (UOW)',
             'Master of Laws (route subject to confirmation)', ('5.1',)),
    Provider('gatehouse-diplomas', 'Gatehouse Level 7 Diplomas',
             'Level 7 Diplomas (4 supplied subjects)', ('6.1',)),
    Provider('qualifi-diplomas', 'Qualifi Diplomas',
             'Level 3 | Level 5 Extended | Level 7', ('6.2', '6.3', '6.4')),
)
SITE_FILES = ('site-homepage', 'edutainment')
# Explicit title reconciliation, not an automatic modification to the owner listing.
COVERAGE_ALIASES = {
    '3.1': {'BA (Hons) in Global Business': 'Global Business'},
    '4.1': {'Global MBA (dual with Rushford Business School)': 'Global MBA'},
    '5.1': {'Master of Laws (LLM)': 'Master of Laws'},
}


@dataclass(frozen=True)
class Faq:
    question: str
    answer: str
    category: str
    bucket: str
    metadata: str
    verification_ids: tuple[str, ...]

    @property
    def plain_answer(self):
        return plain_text(self.answer)

    @property
    def keyword(self):
        match = re.search(r'Target keyword:\s*"(.*?)"', self.metadata, re.S)
        return match[1] if match else ''


def public_markdown(text):
    """Do not expose comments, approval notes or internal verification tables."""
    if text.count('<!--') != text.count('-->'):
        raise ValueError('Unbalanced internal HTML comments')
    text = COMMENT.sub('', text)
    return re.split(r'^## Facts to Verify\b', text, maxsplit=1, flags=re.M)[0].strip()


def parse_faqs(text):
    visible = public_markdown(text)
    raw_questions = list(QUESTION.finditer(text))
    visible_questions = list(QUESTION.finditer(visible))
    result = []
    for index, match in enumerate(visible_questions):
        before = visible[:match.start()]
        headings = re.findall(r'^## (.+)$', before, re.M)
        category = headings[-1] if headings else ''
        last_category = before.rfind('\n## ')
        buckets = re.findall(r'^### (.+)$', before[last_category:], re.M)
        bucket = buckets[-1] if buckets else ''
        end = visible_questions[index + 1].start() if index + 1 < len(visible_questions) else len(visible)
        answer = visible[match.end():end]
        answer = re.split(r'^#{2,3} |^---\s*$', answer, maxsplit=1, flags=re.M)[0].strip()
        raw = raw_questions[index]
        next_raw = raw_questions[index + 1].start() if index + 1 < len(raw_questions) else len(text)
        raw_answer = re.split(r'^#{2,3} |^---\s*$', text[raw.end():next_raw], maxsplit=1, flags=re.M)[0]
        comments = list(COMMENT.finditer(text[:raw.start()]))
        metadata = comments[-1][0] if comments else ''
        ids = tuple(sorted(set(re.findall(r'\b(?:RBS|GAU|UWS|UCA|UOW|GATE|QUAL|HOME|EDU)-\d{2}\b', raw_answer))))
        result.append(Faq(match[1].strip(), answer, category, bucket, metadata, ids))
    return result


def read_faqs(path):
    return parse_faqs(Path(path).read_text(encoding='utf-8'))


def inline_plain(text):
    text = re.sub(r'\[([^\]]+)\]\([^)]+\)', r'\1', text)
    text = re.sub(r'<[^>]+>', '', text)
    text = text.replace('**', '').replace('*', '').replace('`', '')
    return unescape(re.sub(r'\s+', ' ', text)).strip()


def plain_text(markdown):
    """Preserve list items and table relationships without leaking Markdown pipes."""
    lines = public_markdown(markdown).splitlines()
    parts = []
    table = []

    def flush_table():
        if not table:
            return
        headers = table[0]
        for row in table[1:]:
            label = row[0] if row else ''
            values = [f'{headers[i] or "Value"}: {cell}' for i, cell in enumerate(row[1:], 1) if i < len(headers)]
            parts.append(label + ' — ' + '; '.join(values) + '.')
        table.clear()

    for line in lines:
        if line.strip().startswith('|'):
            cells = [inline_plain(cell) for cell in line.strip().strip('|').split('|')]
            if all(re.fullmatch(r':?-{2,}:?', cell) for cell in cells):
                continue
            table.append(cells)
            continue
        flush_table()
        if re.match(r'^\s*(?:- |\d+\. )', line):
            item = re.sub(r'^\s*(?:- |\d+\. )', '', line)
            parts.append(inline_plain(item).rstrip(';') + ';')
        elif line.strip() and not re.match(r'^#{1,3} |^---\s*$', line):
            parts.append(inline_plain(line))
    flush_table()
    return re.sub(r'\s+', ' ', ' '.join(parts)).strip().rstrip(';')


def schema_for(faqs):
    return {
        '@context': 'https://schema.org',
        '@type': 'FAQPage',
        'mainEntity': [
            {'@type': 'Question', 'name': faq.question,
             'acceptedAnswer': {'@type': 'Answer', 'text': faq.plain_answer}}
            for faq in faqs
        ],
    }


def programme_listing():
    text = (ROOT / 'inputs/listing.md').read_text(encoding='utf-8')
    sections = {}
    current = None
    for line in text.splitlines():
        heading = re.match(r'^### Category (\d+\.\d+)\b', line)
        if heading:
            current = heading[1]
            if current in sections:
                raise ValueError(f'Duplicate listing category: {current}')
            sections[current] = []
        programme = re.match(r'^\d+\. (.+)', line)
        if programme and current:
            sections[current].append(programme[1])
    return sections


def normalise(text):
    text = text.casefold().replace('&', ' and ')
    return ' '.join(re.sub(r'[^\w]+', ' ', text).split())


def subject_name(title):
    title = re.sub(r'^(?:Qualifi )?Level \d+\s+', '', title)
    title = re.sub(r'^(?:Extended |Integrated )?Diploma (?:in )?', '', title)
    title = re.sub(r'^(?:Executive MBA|BBA|BSc|MBA|MSc|PhD)\s+(?:in )?', '', title)
    return title


def coverage_results(provider, text=None):
    text = public_markdown(text if text is not None else provider.path.read_text(encoding='utf-8'))
    categories = re.split(r'^## Category \d+[^\n]*\n', text, flags=re.M)[1:]
    listing = programme_listing()
    rows = []
    for index, key in enumerate(provider.listing_categories):
        section = categories[index] if index < len(categories) else ''
        scope = section.split('**Q.', 1)[0]
        # Single-programme titles may be in the heading or the answers; explicit aliases are audited.
        if key in COVERAGE_ALIASES:
            scope = section
        missing = []
        for title in listing[key]:
            subject = COVERAGE_ALIASES.get(key, {}).get(title, subject_name(title))
            if normalise(subject) not in normalise(scope):
                missing.append(title)
        rows.append({'listing_category': key, 'programmes': len(listing[key]), 'missing': missing,
                     'title_reconciliation': key in COVERAGE_ALIASES})
    return rows
