#!/usr/bin/env python3
"""
Generate the client-facing Bachelors page plan: PDF + editable DOCX.

Source of truth for the content lives in this file. Run:
    python3 scripts/gen_page_plan_keywords.py
Outputs:
    landing-page/bachelors/05-page-plan-keywords.pdf
    landing-page/bachelors/05-page-plan-keywords.docx

House rules enforced here:
- zero em dashes anywhere (numeric ranges keep the en dash)
- no header / footer lines, no page numbers
- no meta text about the document itself, no internal K/R tags
- simple English, facts only
"""

# ---------------------------------------------------------------- palette
NAVY = "071444"
RED = "b20202"
INK = "1c1e26"
GREY = "5a6070"
PAPER = "f5f0eb"
PAPER_DEEP = "e4dcd0"
TINT = "eef1f8"
GOLD = "c9a227"

ORG_LINE = "Maverick Business Academy (London)  \u00b7  UAE office: Robot Park Tower, Sharjah  \u00b7  September 2026"

# ---------------------------------------------------------------- content
TITLE_1 = "Bachelors Landing Page (UAE / GCC)"
TITLE_2 = "Page Plan & Keywords"
SUBTITLE = ("A plain-language overview of the new bachelors page: what it is, who it is for, "
            "what each section will contain, how it will be found online, and the complete list "
            "of search phrases it is built around.")

CONTENTS = [
    "The page in one minute: what it is and the four study routes it offers",
    "Who it is for: the three audiences it speaks to",
    "The market at a glance: the numbers that make this page worthwhile",
    "What the page promises: the five messages it carries, in order",
    "The page, section by section: all 15 sections, with chart previews in three of them",
    "How the page will be found online",
    "The search phrases (keywords): the complete two-tier list",
]

S1_INTRO = ("A single, focused web page for undergraduate (bachelors) study routes, aimed at people "
            "living in Dubai, Sharjah, Abu Dhabi and across the GCC, including Gulf-based expats from "
            "India, Pakistan, the Philippines and Egypt. Anyone who reads the page and wants to know "
            "more can start in under a minute: fill in a five-field enquiry form, or message us on WhatsApp.")
S1_OUTRO = ("The page will live at /bachelors-dubai on our website, a short address that exactly "
            "matches the main search phrase (see the keyword list at the end).")

ROUTES = [
    ("BBA: Bachelor of Business Administration",
     "From Rushford Business School, Switzerland (EduQua quality label, IACBE accreditation, 5-star QS "
     "rating for online learning), with a dual certification from Maverick London. Seven specialisations: "
     "Business Administration, Marketing, Finance, Human Resources, Management Information Systems, "
     "Hospitality & Tourism, and Project Management. For fresh 12th-pass students and working professionals."),
    ("BSc: Bachelor of Science",
     "From Girne American University, North Cyprus (recognised by Y\u00d6DAK and Turkey's Y\u00d6K; business "
     "programmes IACBE accredited). Ten programmes: Business Management, Tourism & Hospitality, International "
     "Business, Psychology, Accounting & Finance, Marketing, Human Resources, Accounting, Economics and "
     "Management Information Systems."),
    ("BA (Hons): Global Business",
     "A full UK honours degree from the University of the West of Scotland (UK). The straightforward choice "
     "for anyone who specifically wants a UK-awarded bachelor's degree."),
    ("Top-up route to a UK honours degree",
     "For people who already hold a diploma, HND or credit-bearing experience: an Ofqual/QUALIFI Level 5 or 7 "
     "diploma completed in the UK system, then topped up into a recognised UK honours degree. The fastest "
     "legitimate way to reach degree level. The awarding university will be stated on the page."),
]

S2_INTRO = "Three audiences, one page. Every section is written so that each of them finds what they need."
PERSONAS = [
    ("The new graduate and their family",
     "Just finished 12th grade, or one to two years out. The student wants a real career; the parents are the "
     "co-decision-makers and they pay. They care about four things: is the degree real and recognised, what "
     "does it cost, is the student safe and supported, and will employers accept it? The page answers all four, clearly."),
    ("The working professional (22\u201332)",
     "Already earning, and not willing to give up a salary for a four-year campus grind. Needs a degree that fits "
     "around a job: online or weekend-tolerant delivery, no visa, no relocation, with a person to call when "
     "something is unclear."),
    ("The top-up candidate",
     "Has a diploma, HND or several years of experience, and wants the fastest legitimate route to a recognised "
     "honours degree. The most ready-to-act buyer in the market. The page dedicates a route to them."),
]

S3_INTRO = "Why this page is worth building. The numbers behind the opportunity:"
S3_BULLETS = [
    ("", "UAE higher education admitted 57,035 new students in 2024\u201325, up 13%, the highest intake in a "
         "decade (Ministry of Higher Education data)."),
    ("", "Dubai's private universities grew 20%, and international students rose 29% to 35% of the student body "
         "(KHDA, Dubai's education regulator)."),
    ("", "Around 42% of Dubai's international students are Indian, concentrated in business and technology. This "
         "is exactly the profile our BBA and BSc routes serve (2026 industry market report). Business & economics "
         "is the most-pursued field in the UAE."),
    ("", "What people find today when they search \u201cbachelors in Dubai\u201d: campus-premium schools (AED "
         "35,000\u201365,000 a year, plus visa and relocation) and 12-month \u201cBBA\u201d fast-tracks (AED "
         "3,500\u201325,000 total). Nobody credible offers named universities, no visa, weekend-tolerant study, "
         "AED instalments and a real office in the Gulf. That gap is this page."),
    ("", "Since 2023 the UAE Ministry of Education has recognised online degrees, and in March 2025 the Ministry "
         "of Higher Education added a formal recognition route for them (Gulf News, 10 March 2025). The page will "
         "state this fact, sourced and dated. No competitor currently does."),
]

S4_INTRO = ("Five messages, in the order the page presents them. Every section of the page supports one or more "
            "of these:")
PROMISES = [
    ("Real, named universities.",
     "Every route states exactly which university awards the degree and its accreditations: University of the "
     "West of Scotland (UK), Rushford Business School (Switzerland), Girne American University (North Cyprus) and "
     "the top-up awarding university. No vague \u201cpartner universities\u201d language."),
    ("No student visa.",
     "Online routes need no student visa. The student stays in their city: Dubai, Sharjah, Abu Dhabi, or anywhere in the GCC."),
    ("Flexible by design.",
     "Weekend-tolerant scheduling, online and hybrid delivery, and rolling intakes, built around a job and a "
     "family, not the other way round."),
    ("Honest AED pricing.",
     "Fees shown in AED with an instalment plan, and compared openly with what a campus degree costs and what a "
     "12-month fast-track costs, including what each one risks."),
    ("A local, accountable team.",
     "An office in Sharjah (Robot Park Tower), a London head office, and a counselling practice running since "
     "2012. Every enquiry gets a phone call back from a person within one business day."),
]

S5_INTRO = ("From top to bottom, these are the 15 sections a visitor will see, each with its category, its real "
            "heading, a one-line description and its full scope. Trust is built before fees are shown, and proof "
            "comes before the final ask. The website's existing navigation bar and footer stay exactly as they are "
            "today; the list below covers only the new content of the page. Three sections carry previews of the "
            "statistics charts that will appear there, drawn in the brand colours with the source always visible.")

# charts --------------------------------------------------------------
CHART_A = {
    "kind": "pie",
    "caption": "Preview: who is studying in Dubai's private universities",
    "slices": [("International students: 35% of the student body, up 29% in one year", 35, NAVY),
               ("Local students: 65%", 65, PAPER_DEEP)],
    "source": "Source: KHDA (Dubai's education regulator), 2024\u201325 academic year.",
}
CHART_B = {
    "kind": "pie",
    "caption": "Preview: the programme mix, from the client catalogue",
    "slices": [("BBA: 7 specialisations (39%)", 39, RED),
               ("BSc: 10 programmes (56%)", 56, NAVY),
               ("BA (Hons) Global Business: 1 programme (5%)", 5, GOLD)],
    "source": "Source: the client's programme catalogue (September 2026), 18 named undergraduate programmes.",
}
CHART_C = {
    "kind": "bar",
    "caption": "Preview: the real cost of a bachelor's degree (AED, total degree)",
    "bars": [("Campus (typical, 3-yr total)", 105, 195),
             ("Premium campus (S P Jain, 4-yr)", 100, 100),
             ("12-month fast-track \u201cBBA\u201d", 3.5, 25),
             ("Maverick (your confirmed total)", None, None)],
    "source": ("All competitor bars from verified public listings: campus band AED 35k\u201365k/yr \u00d7 3 years "
               "(Fateh, Jan 2026); S P Jain online AED 100,000 / 4 yrs (educations.com, Sep 2026); fast-track AED "
               "3,500\u201325,000 total (coursetakers.ae fee table, Apr 2026). No number on this chart is invented, "
               "and the source line stays on the live page."),
}

BLOCKS = [
    ("1. Top announcement strip", "SEARCH & ATTENTION",
     "Next intake: [Month 2026]  \u00b7  No student visa needed for online routes",
     "The first line the visitor sees: a real intake date and the no-visa promise.",
     "One line at the very top of the page: the next intake month, and the promise \u201cNo student visa needed "
     "for online routes\u201d.", None),
    ("2. Opening block (the headline)", "CONVERSION + SEARCH",
     "Your Bachelor's Degree in Dubai: From Named Universities, No Student Visa",
     "The page's core message, four trust points, and two ways to respond.",
     "The page's main message: a real degree from a named university that fits a Gulf life. Four quick trust "
     "points sit right under the headline: named universities, no student visa, weekend-tolerant study, office in "
     "Sharjah. Two ways to respond from the very first screen: a short enquiry form, or WhatsApp.", None),
    ("3. Recognition strip", "TRUST & RECOGNITION",
     "Where Your Degree Comes From, and Why It's Recognised in the UAE",
     "Named universities, their accreditations, and the 2023/2025 UAE recognition facts, answered before the visitor asks.",
     "Immediately after the opening, because for a bachelors buyer, recognition is question number one. The "
     "universities by name with their accreditations, the Ofqual/QUALIFI diploma route, the 2023/2025 UAE "
     "recognition facts, London head office, Sharjah office, and \u201csince 2012\u201d.", None),
    ("4. The problem we solve", "RELEVANCE + DEMAND",
     "The Real Questions Behind Every Enquiry, and the Honest Answers",
     "Fear-to-answer pairs for the student and the parent, plus a real demand snapshot showing this path is now the norm.",
     "What the student is afraid of (12-month mills, visa hassle, a 3\u20134 year campus grind) and what the "
     "parent is afraid of (will it be recognised? attestation? what will employers say? cost blowing up), with "
     "the page's answer to each fear, one by one. The pie chart adds the market fact that makes it safe to choose "
     "this path.", CHART_A),
    ("5. Who it's for", "RELEVANCE / ROUTING",
     "Three Ways to Start Your Bachelor's: Choose Your Route",
     "Three persona cards, each pointing the visitor to the right route for them.",
     "Three simple cards: the new graduate + family, the working professional, the top-up candidate, each "
     "pointing to the right route for them.", None),
    ("6. Programme overview", "OFFER",
     "BBA, BSc, BA (Hons) & Top-up: Your Bachelors Options in One View",
     "The four routes side by side with duration and study format. The pie chart shows the real programme mix from the catalogue.",
     "The four routes, BBA, BSc, BA (Hons) and the Top-up, side by side, each with its duration and study format, "
     "so a visitor can orient themselves in one look.", CHART_B),
    ("7. Full programme list", "OFFER / DETAIL",
     "All Programmes at a Glance: Pick Your Specialisation",
     "Every specialisation listed, with the most in-demand ones marked.",
     "All the specialisations (the 7 BBA options, the 10 BSc programmes, the BA (Hons) and the top-up options), "
     "with the most in-demand ones marked.", None),
    ("8. How it works", "LOGISTICS / CONVERSION",
     "How It Works: From Your Enquiry to Your First Class",
     "Four simple steps, a real intake calendar, and the one-business-day call promise.",
     "Four simple steps: enquiry, a counselling call within one business day, documents and intake, you start. "
     "Plus the intake calendar, so visitors see real dates.", None),
    ("9. Recognition & careers", "TRUST & RECOGNITION",
     "Is an Online Bachelor's Degree Valid in the UAE? The Sourced Answer",
     "The exact phrase parents type, answered with the sourced 2023/2025 facts, the attestation route, and the careers each route leads to.",
     "A plain-words explanation of how UAE recognition works (private sector versus government/regulated roles), "
     "the attestation route for UK degrees, and the careers each route leads into: roles, not salary promises.", None),
    ("10. Fees & payment", "OBJECTION / PRICE",
     "Bachelors Fees in the UAE, in AED: Ours, and the Honest Comparison",
     "Our confirmed AED fees with the instalment plan. The bar chart compares the total cost of every route using verified public prices.",
     "Our confirmed AED fees per route with the instalment plan, and an honest comparison against campus fees and "
     "12-month fast-tracks, including the recognition risk that comes with them.", CHART_C),
    ("11. Comparison", "OBJECTION / DECISION",
     "Campus vs Online vs 12-Month Fast-Track, Side by Side",
     "Three options compared on six rows, in plain terms.",
     "Three columns in plain terms: our flexible route versus a traditional campus degree versus a 12-month "
     "fast-track, compared on recognition, visa, time, cost, the support you get, and progression to an MBA "
     "afterwards.", None),
    ("12. Proof", "PROOF / TRUST",
     "Real Students, Real Stories, and the Sharjah Office in Real Life",
     "Two to three named, permissioned student stories with verified numbers and real office photos.",
     "Two to three real student stories, named and with permission, along with verified numbers and photos from "
     "the Sharjah office. No stock-photo graduates.", None),
    ("13. People & office", "LOCAL TRUST",
     "Your Admissions Team in Sharjah, Backed by Our London Head Office",
     "Names, photos, the Robot Park Tower map pin, and \u201csince 2012\u201d.",
     "The admissions team with names and photos, the Sharjah office at Robot Park Tower with a map pin, the "
     "London head office line, and \u201csince 2012\u201d.", None),
    ("14. Frequently asked questions", "OBJECTION / SEARCH",
     "Frequently Asked Questions: Bachelors in the UAE",
     "Ten plain-language answers, each matching a real search question. This is what powers the FAQ box in Google results.",
     "Ten questions answered in plain language: is it recognised? do I need a visa? how long does it take? what "
     "are the fees and instalments? can I study while working? how does the top-up work? how does attestation "
     "work? when are intakes? can I do an MBA afterwards? and the one question parents always ask.", None),
    ("15. Final call to action + enquiry form", "CONVERSION",
     "Start Your Bachelor's Journey: Free Enquiry, a Call Within One Business Day",
     "Five fields only, the four-beat reassurance line, and a clear next step after submitting.",
     "The closing ask. Five fields only: name, phone/WhatsApp, email, programme, country. Under the button: free, "
     "no obligation, a call within one business day, and your details never shared. After submitting, the visitor "
     "sees exactly what happens next and gets a WhatsApp shortcut. The page then closes with the website's "
     "standard footer, unchanged.", None),
]

S6_INTRO = ("A good page also needs to be findable. This is how the page becomes easy to reach, on Google and "
            "everywhere else a student looks, so reach grows steadily instead of depending on one channel.")
S6_PARTS = [
    ("Built to be found, from day one",
     "The web address matches the main search phrase exactly (/bachelors-dubai). The page title is built on that "
     "phrase, the section headings are written the way people ask their questions, and structured data sits behind "
     "the scenes so that Google can show fees, duration and FAQ answers directly inside the search results. "
     "Everything is designed for phones first, because the large majority of Gulf searches happen on mobile, and "
     "the page is built to load fast."),
    ("The Sharjah office on Google Maps",
     "The Robot Park Tower office gets a Google Business Profile, so we appear on Maps and on local searches like "
     "\u201cbachelors near me\u201d or \u201cbachelor degree in Sharjah\u201d, with the phone number and WhatsApp "
     "one tap away. The office's name, address and phone are kept identical on every listing we create, which is "
     "exactly what Google rewards with better local rankings."),
    ("Listing platforms & directories",
     "Profiles on the major education listing sites where UAE students already browse: educations.com, "
     "coursetakers.ae, bachelorsportal and similar. Each profile is an extra door into the page and adds "
     "third-party credibility; we create one per study route, all pointing back to the page."),
    ("Guide articles around the page",
     "Two or three support pages that answer the big research questions: \u201cBachelors in UAE: a complete "
     "guide\u201d, \u201cIs an online bachelor's degree valid in the UAE?\u201d and \u201cCampus vs online vs "
     "12-month bachelors\u201d. This is the same approach that already ranks our Masters guide, and each guide "
     "links back to the page. These capture the searches that happen before someone is ready to enquire. They feed "
     "the page a steady stream of readers over time."),
    ("Social & paid reach (after launch)",
     "Facebook and Instagram for students and parents, with targeting tuned for the Indian-expat community; "
     "WhatsApp click-to-chat ads so a tap opens a conversation directly; and retargeting for people who visited "
     "but didn't enquire. LinkedIn for the working-professional audience. Scope and budget to be agreed "
     "separately. None of it is required for the page itself to start ranking."),
    ("A view anyone can read",
     "A simple weekly set of numbers: visits, Google impressions and clicks, form starts, WhatsApp clicks and "
     "calls. No jargon, just enough to see clearly what is working and double down on it."),
]

S7_INTRO = ("Two tiers, chosen from live UAE/GCC search results (September 2026) for reach and how ready-to-buy "
            "the searcher is. Tier 1 (K1\u2013K8) sets the page's title and main headings. Tier 2 (R1\u2013R8) is "
            "woven naturally into the copy, the FAQ and the guide pages. Every phrase has exactly one home, so the "
            "writing never reads like padding.")

TIER1 = [
    ("K1", "bachelor's degree in Dubai",
     "The biggest search in this market. Today it is answered by content sites, not by a real academy. A trusted "
     "page with named universities can take it. Where it appears: the page title, the main headline area, the "
     "programme sections and the FAQ."),
    ("K2", "online bachelor's degree in UAE",
     "The highest lead quality of all eight: the person searching this wants exactly what we offer, online, no "
     "relocation. Where it appears: the headline sub-line, the recognition sections and the FAQ."),
    ("K3", "BBA in Dubai / BBA in UAE",
     "The most-searched degree name at undergraduate level, with very high volume. Where it appears: the programme "
     "cards, the fees section and the FAQ."),
    ("K4", "top universities in Dubai for bachelor's",
     "Top-of-funnel: students comparing universities before they decide. We capture them with the \u201cwhere "
     "you'll study\u201d part of the recognition section and the comparison section, rather than by arguing with "
     "university rankings sites."),
    ("K5", "bachelor's degree in Sharjah",
     "Our unique asset: our UAE office is in Sharjah. Local search brings proximity trust that no online-only "
     "consultancy can match. Where it appears: the recognition strip, the people & office section, and local advertising."),
    ("K6", "no visa required bachelor's degree UAE",
     "Nobody in the market currently owns this phrase, and it answers the number-one objection for both the "
     "student and the parent. Where it appears: the headline sub-line, the FAQ and the page's summary line that "
     "search engines show."),
    ("K7", "affordable bachelor's degree in UAE / bachelor fees",
     "Price-first searchers. A large share of them are Indian expats. We win this audience with AED transparency "
     "and an honest comparison, not with a low price. Where it appears: the fees section, the comparison and the FAQ."),
    ("K8", "top-up bachelor's degree UAE",
     "A smaller search but the highest conversion: the searcher is ready and time-boxed. Where it appears: the "
     "dedicated top-up route card, the FAQ, and a secondary page title option."),
]
TIER2 = [
    ("R1", "bachelor's degree in Abu Dhabi",
     "The same buyer, a different emirate. Abu Dhabi is named naturally in the copy, and the phrase gets its own "
     "local ad set later."),
    ("R2", "online BBA in UAE / study BBA online",
     "The degree name plus the online intent, the natural next step after K3. Woven into the BBA cards and the FAQ."),
    ("R3", "online BSc in UAE / bachelor of science online",
     "The BSc rail gets its own phrase so the ten BSc programmes are findable on their own. Woven into the BSc "
     "cards and the FAQ."),
    ("R4", "bachelor's degree in UAE for Indians",
     "The largest expat segment often searches with their home country in the phrase. A dedicated line for the "
     "Indian-expat audience appears on the page, and the guide articles carry this phrase in their titles."),
    ("R5", "is an online bachelor's degree valid in the UAE / online degree recognition UAE",
     "The parent's exact search, and our strongest answer: the sourced 2023/2025 recognition facts. The "
     "recognition section, the FAQ and the \u201cvalid in the UAE?\u201d guide all answer it."),
    ("R6", "bachelors in Ajman / bachelors near me",
     "The Sharjah office also serves Ajman and the eastern emirates. Covered by the Google Maps profile and local "
     "copy. No separate page needed."),
    ("R7", "UK bachelor degree online from UAE / study UK degree online from Dubai",
     "The UK rail: pairs with the BA (Hons) route and the recognition strip. Woven into the recognition section "
     "and the FAQ."),
    ("R8", "top-up degree in UAE / complete a bachelor's degree fast",
     "The top-up rail, widened: captures both the \u201ctop-up\u201d searchers and the \u201cI need my degree "
     "quickly\u201d searchers. Woven into the top-up route card and the FAQ."),
]
SUPPORTING = ("Supporting phrases woven through the copy (never headlined): weekend classes \u00b7 study while "
              "working in the UAE \u00b7 AED instalments \u00b7 rotational intakes \u00b7 Ofqual \u00b7 QUALIFI "
              "\u00b7 degree attestation \u00b7 MOHRE \u00b7 English-medium \u00b7 credit transfer \u00b7 "
              "progression to MBA \u00b7 Robot Park Tower \u00b7 London head office \u00b7 since 2012")


def all_text():
    """Yield every content string for the no-em-dash assertion."""
    items = [TITLE_1, TITLE_2, SUBTITLE, ORG_LINE, S1_INTRO, S1_OUTRO, S2_INTRO, S3_INTRO,
             S4_INTRO, S5_INTRO, S6_INTRO, S7_INTRO, SUPPORTING]
    items += CONTENTS + [t for _, t in ROUTES] + [t for _, t in PERSONAS]
    items += [b for _, b in S3_BULLETS]
    for lead, body in PROMISES:
        items += [lead, body]
    for name, cat, heading, desc, scope, _ in BLOCKS:
        items += [name, cat, heading, desc, scope]
    for cap_key in ("caption", "source"):
        for ch in (CHART_A, CHART_B, CHART_C):
            items.append(ch[cap_key])
    for ch in (CHART_A, CHART_B):
        items += [s[0] for s in ch["slices"]]
    items += [label for label, _, _ in CHART_C["bars"]]
    for title, body in S6_PARTS:
        items += [title, body]
    for code, phrase, body in TIER1 + TIER2:
        items += [code, phrase, body]
    return items


def assert_clean():
    for t in all_text():
        assert "\u2014" not in t, f"em dash found in: {t[:80]}"
        assert "  " not in t or "\u00b7" in t, f"double space in: {t[:80]}"


if __name__ == "__main__":
    assert_clean()
    from gen_page_plan_pdf import build_pdf
    from gen_page_plan_docx import build_docx
    build_pdf()
    build_docx()
    print("done")
