"""Master's Programs List PDF: University -> Master's category -> Programs.

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
from reportlab.platypus import Paragraph, SimpleDocTemplate, Spacer
from xml.sax.saxutils import escape

OUT = "landing-page/gulf-masters/12-masters-programs-list-university-wise.pdf"

NAVY = colors.HexColor("#071444")
RED = colors.HexColor("#B20202")
INK = colors.HexColor("#1C1E26")
GREY = colors.HexColor("#5A6070")
LIGHT = colors.HexColor("#EEF2FA")

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
    S["title"] = ParagraphStyle("t", fontName="Helvetica-Bold", fontSize=17,
                                textColor=NAVY, leading=21, spaceAfter=2)
    S["sub"] = ParagraphStyle("s", fontName="Helvetica", fontSize=9.5,
                              textColor=GREY, leading=13, spaceAfter=10)
    S["uni"] = ParagraphStyle("u", fontName="Helvetica-Bold", fontSize=12.5,
                              textColor=NAVY, leading=16, spaceBefore=12,
                              spaceAfter=4, backColor=LIGHT,
                              borderPadding=(4, 4, 4, 4))
    S["cat"] = ParagraphStyle("c", fontName="Helvetica-Bold", fontSize=10.5,
                              textColor=RED, leading=14, spaceBefore=7,
                              spaceAfter=2)
    S["prog"] = ParagraphStyle("p", fontName="Helvetica", fontSize=10,
                               textColor=INK, leading=13.5, leftIndent=14,
                               spaceAfter=1.5)
    S["small"] = ParagraphStyle("sm", fontName="Helvetica", fontSize=8.5,
                                textColor=GREY, leading=11, spaceAfter=4)

    story = [
        Paragraph("Master's Programs List", S["title"]),
        Paragraph("University &nbsp;\u2192&nbsp; Master's program category &nbsp;\u2192&nbsp; Master's programs &nbsp;\u00b7&nbsp; Maverick &nbsp;\u00b7&nbsp; 16 September 2026", S["sub"]),
    ]

    total = 0
    for uni, cats in UNIVERSITIES:
        n_uni = sum(len(p) for _, p in cats)
        total += n_uni
        story.append(Paragraph(f"{escape(uni)} &nbsp;({n_uni} master's programs)", S["uni"]))
        for cat, progs in cats:
            story.append(Paragraph(f"{escape(cat)} &nbsp;({len(progs)})", S["cat"]))
            for i, p in enumerate(progs, 1):
                story.append(Paragraph(f"{i}.&nbsp; {escape(p)}", S["prog"]))
    story.append(Spacer(1, 8))
    story.append(Paragraph(f"Total master's programs listed: {total}. Source: official programme "
                           "listing (uploads/listing.pdf), master's level only, plus the approved "
                           "University of the West of Scotland addition.", S["small"]))

    doc = SimpleDocTemplate(OUT, pagesize=A4, leftMargin=2.0 * cm,
                            rightMargin=2.0 * cm, topMargin=1.8 * cm,
                            bottomMargin=1.8 * cm,
                            title="Master's Programs List", author="Maverick")
    doc.build(story)
    print(f"universities {len(UNIVERSITIES)} | total master's programs {total}")
    print("wrote", OUT)


if __name__ == "__main__":
    main()
