"""Master's Programs List PDF (strict format).

Output contains ONLY the list, exactly as:
    University name ->
    Program category ->
    Program name
    Program name
    ...
No title, no counts, no notes, nothing else on the PDF.

Source of truth: uploads/listing.pdf (master's level only) plus the
client-approved addition (University of the West of Scotland, MBA in
International Business).

Usage: python3 scripts/gen_masters_programs_list_pdf.py
Writes: landing-page/gulf-masters/12-masters-programs-list-university-wise.pdf
"""
from reportlab.lib import colors
from reportlab.lib.pagesizes import A4
from reportlab.lib.styles import ParagraphStyle
from reportlab.lib.units import cm
from reportlab.platypus import Paragraph, SimpleDocTemplate
from xml.sax.saxutils import escape

OUT = "landing-page/gulf-masters/12-masters-programs-list-university-wise.pdf"
ARROW = " \u2192"

NAVY = colors.HexColor("#071444")
RED = colors.HexColor("#B20202")
INK = colors.HexColor("#1C1E26")

UNIVERSITIES = [
    ("Rushford Business School (RBS), Switzerland", [
        ("MBA", [
            "MBA in Sustainability, Energy and Environment",
            "MBA in Strategic Management",
            "MBA in Real Estate Management",
            "MBA in Human Resource Management",
            "MBA in Marketing",
            "MBA in Logistics & Supply Chain Management",
            "MBA in Healthcare Leadership",
            "MBA in Hospitality & Tourism Management",
            "MBA in Health Economics",
            "MBA in Entrepreneurship and Innovation",
            "MBA in Finance",
            "Master of Business Administration (MBA)",
        ]),
        ("MSc", [
            "MSc in Sustainability and Environmental Management",
            "MSc in Strategic Management",
            "MSc in Operations and Supply Chain Management",
            "MSc in International Business Management",
            "MSc in Marketing",
            "MSc in Entrepreneurship & Innovation",
            "MSc in Finance and Investment",
            "MSc in Economics",
            "MSc in Business Management",
        ]),
    ]),
    ("Girne American University (GAU), North Cyprus", [
        ("MBA", [
            "MBA in Business Management",
            "MBA in Financial Management",
            "MBA in International Business Management",
            "MBA in Management Information Systems",
            "MBA in Marketing",
            "MBA Data Science/Analytics Management",
        ]),
        ("Executive MBA (EMBA)", [
            "Executive MBA in Educational Leadership",
            "Executive MBA in Media & Entertainment",
            "Executive MBA in Global Banking & Finance",
            "Executive MBA in Health & Safety Leadership",
            "Executive MBA in Renewable Energy & Sustainability",
            "Executive MBA in Tourism & Hospitality Management",
            "Executive MBA in Innovation & Entrepreneurship",
            "Executive MBA in Project Management",
            "Executive MBA in Human Resources Management",
            "Executive MBA in Supply Chain Management",
            "Executive MBA in Health Care Management",
            "Executive MBA in Engineering Management",
            "Executive MBA in Public Administration",
            "Executive MBA in Public Health",
            "Executive MBA in Digital Marketing",
            "Executive MBA in Sport Management",
        ]),
        ("MSc (with Thesis)", [
            "MSc in Business Management",
            "MSc in Economics",
            "MSc in Healthcare Management",
            "MSc in Counselling Psychology",
        ]),
    ]),
    ("University for the Creative Arts (UCA), UK", [
        ("Global MBA (with Rushford Business School, Switzerland)", [
            "Global MBA",
        ]),
    ]),
    ("University of Wolverhampton (UOW), UK", [
        ("Master of Laws", [
            "Master of Laws",
        ]),
    ]),
    ("University of the West of Scotland (UWS), UK", [
        ("MBA", [
            "MBA in International Business",
        ]),
    ]),
]


def main():
    S = {}
    S["uni"] = ParagraphStyle("u", fontName="Helvetica-Bold", fontSize=12.5,
                              textColor=NAVY, leading=17, spaceBefore=14,
                              spaceAfter=3)
    S["cat"] = ParagraphStyle("c", fontName="Helvetica-Bold", fontSize=10.5,
                              textColor=RED, leading=15, spaceBefore=8,
                              spaceAfter=2, leftIndent=10)
    S["prog"] = ParagraphStyle("p", fontName="Helvetica", fontSize=10,
                               textColor=INK, leading=14, leftIndent=24,
                               spaceAfter=1)

    story = []
    first = True
    for uni, cats in UNIVERSITIES:
        st = S["uni"]
        if first:
            st = ParagraphStyle("u0", parent=st, spaceBefore=0)
            first = False
        story.append(Paragraph(escape(uni) + ARROW, st))
        for cat, progs in cats:
            story.append(Paragraph(escape(cat) + ARROW, S["cat"]))
            for p in progs:
                story.append(Paragraph(escape(p), S["prog"]))

    doc = SimpleDocTemplate(OUT, pagesize=A4, leftMargin=2.0 * cm,
                            rightMargin=2.0 * cm, topMargin=1.8 * cm,
                            bottomMargin=1.8 * cm)
    doc.build(story)
    n = sum(len(p) for _, c in UNIVERSITIES for _, p in c)
    print(f"universities {len(UNIVERSITIES)} | programs {n} | strict format")
    print("wrote", OUT)


if __name__ == "__main__":
    main()
