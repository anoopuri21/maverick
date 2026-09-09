"""Detect internal delivery/approval context without suppressing legal qualifications."""
from html import unescape
import re

# Do not ban "approved" or "approval" alone: visa decisions and recognised awarding
# organisations are substantive information, not an editorial sign-off banner.
PATTERNS = tuple(re.compile(pattern, re.I) for pattern in (
    r'\b(?:client|owner)[-\s]+(?:approval|review|sign[-\s]?off|requested|request|ready|facing|approved|authori[sz]ed|delegated)\b',
    r'\b(?:for|awaiting|pending)\s+(?:client|owner|internal)\s+(?:review|approval)\b',
    r'\b(?:awaiting|pending|before|requires?|requesting)\s+(?:(?:client|owner|publication)\s+)?approval\b',
    r'\b(?:owner exception|owner mandate|owner instructions?|approval_id|client decision)\b',
    r'\b(?:not for publication|website publication|publication status|publication approval|publish[-\s]blockers?)\b',
    r'\b(?:unpublished|on hold|review[-\s]only|review export|review set|draft FAQ|FAQ draft|proposed review)\b',
    r'\bconfidential\s*(?:\.|[-–—]\s*for)\s*',
    r'\b(?:CMS|GitHub|codebase|repository|seeders?)\b',
    r'\blocal\s+(?:schema|review|artefacts?|exports?)\b',
    r'\bIMM-US-UK-\d{4}-\d{2}-\d{2}\b',
    r'\[VERIFY\b|Facts to Verify|Target keyword:|<!--|\bSTATUS:',
))


def presentation_issues(text):
    # A legacy filename in a link target is not reader-facing approval wording.
    text = re.sub(r'\[([^\]]+)\]\([^)]+\)', r'\1', text)
    text = re.sub(r'\s+', ' ', unescape(text))
    return sorted({match.group(0) for pattern in PATTERNS for match in pattern.finditer(text)})
