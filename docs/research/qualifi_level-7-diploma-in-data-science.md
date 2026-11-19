# Research Dossier — Qualifi Level 7 Diploma in Data Science

**Tracker S.No:** 66
**Awarding body:** Qualifi Ltd (United Kingdom)
**Research date:** 2026-10-07
**Status:** Research Complete (with declared gaps)

> Read with `qualifi_level-7-SHARED.md` (master's-route rule, entry patterns) and
> `qualifi_SHARED-FACTS.md`.

---

## 1. Source URLs

| # | URL | Fetched | Use |
|---|---|---|---|
| 1 | `https://qualifi.net/qualifi-level-7-diploma-data-science/` | 2026-10-07 | **URL RESOLVED.** Everything below |
| 2 | `https://qualifi.net/qualifications/` | earlier | QAN cross-check: 603/6693/X ✅ matches |
| 3 | `https://qualifi.net/wp-content/uploads/2020/10/Qualifi-Level-7-Diploma-in-Data-Science.pdf` | NOT fetched | Centre Specification (October 2020) |
| 4 | `https://qualifi.net/wp-content/uploads/2020/10/Level-7-Diploma-in-Data-Science.pdf` | NOT fetched | Course Brochure — do not cite |

✅ **URL QUERY RESOLVED.** The catalogue listed this with a doubled path segment
(`qualifi.net/qualifi.net/...`). The de-duplicated path resolves correctly. Note the
slug omits "in", unlike most Level 7 slugs.

---

## 2. Regulated data (verbatim)

| Field | Value |
|---|---|
| Qualification title | Qualifi Level 7 Diploma in Data Science |
| Qualification type | Vocational Related Qualification (Higher Education) |
| Level | 7 |
| Accreditation status | Accredited |
| Credit Equivalency | **120** |
| TQT | NOT PUBLISHED |
| Qualification number (RQF) | 603/6693/X |
| Progression routes | "A Qualifi Level 7 and/or 8 Diploma, directly into employment in an associated profession, or an appropriate dissertation-only for a Master's Degree at one of our University partnerships." |
| Availability | UK and international |

⚠️ Mentions a **Level 8** Qualifi diploma as a progression route — the only reference to
Level 8 seen so far. No Level 8 row exists in the tracker; context only.

⚠️ Master's route: apply `GAP-SOURCE-QF-31` in full (see Level 7 shared file §1).

---

## 3. Qualification Overview (verbatim)

> "With the emergence of cloud computing, big data and artificial intelligence, data
> science has become a key fourth generation profession. The Level 7 Postgraduate
> Diploma in Data Science has been developed to prepare aspiring Data Scientists, Data
> Analysts and Artificial Intelligence specialists to take advantage of the growing
> business and employment opportunities in these fields.
>
> The Diploma is designed to enable learners to gain skills in maths, statistics and
> programming in R, Python and SQL. The Diploma also provides a sound basis for a
> progression to Masters Degrees in a number of relevant disciplines."

**Bespoke copy, not boilerplate.** Two points of note:
- ⭐ **Named technologies: R, Python and SQL.** This is the only Qualifi page seen that
  names specific programming languages. Highly concrete and useful — use it.
- ⚠️ Qualifi calls it "The Level 7 **Postgraduate** Diploma" in the overview while the
  official title is simply "Level 7 Diploma". Use the official title; do not promote the
  award to "Postgraduate Diploma" on the strength of loose body copy.
  → **GAP-SOURCE-QF-32.**
- ⚠️ "Key fourth generation profession" is unexplained marketing language. Do not repeat.

---

## 4. Units as published — mis-split lines

Qualifi prints five bullets:

1. Exploratory Data Analysis
2. Statistical Inference
3. **Fundamentals of Predictive Modelling Advanced Predictive Modelling**
4. Time Series Analysis
5. **Unsupervised Multivariate Methods Machine Learning**

⚠️ Items 3 and 5 each clearly contain **two** unit titles run together:
"Fundamentals of Predictive Modelling" + "Advanced Predictive Modelling", and
"Unsupervised Multivariate Methods" + "Machine Learning". Learning outcome 5 names
predictive modelling and data reduction separately, and outcome 6 names machine learning
separately, which supports the split reading. That gives **seven** units, not five.

**Do not silently correct the awarding body's list.** Present what is published, state
that two lines appear to contain two unit titles each, and point to the Centre
Specification. Same class of defect as QF-07 (S.No 32), QF-14 (S.No 37) and QF-22
(S.No 41). → **GAP-SOURCE-QF-33.**

---

## 5. Learning Outcomes (8, verbatim substance)

1. Gain the mathematical and statistical knowledge and understanding required to carry out basic and advanced data analysis.
2. Develop sufficient skill in the R, Python and SQL programming languages to carry out data analysis to an advanced level.
3. Develop a strong understanding of data management, including evaluation, structuring and cleaning of data for analysis.
4. Become familiar with and use the tools and techniques used in data visualisation.
5. Develop a comprehensive knowledge of classical data analytics, including statistical inference, predictive modelling, time series analysis and data reduction.
6. Become familiar with and apply common machine learning techniques to business and other problems in order to uncover options and solutions.
7. Develop an understanding of essential concepts from contemporary themes in business.
8. Understand, evaluate and apply data science and analytics within business and organisational contexts.

**Unusually strong outcomes for Qualifi** — specific, technical, verifiable, and not the
generic eight-item set recycled across its business and IT awards. Data cleaning
(outcome 3) is a welcome, realistic inclusion.

---

## 6. Entry Requirements (verbatim)

- A minimum of a Level 6 qualification in a related sector or;
- Bachelors degree or;
- **A minimum of 3 years' work experience which demonstrates current and relevant industry knowledge.**

The three-year experience route is a genuine alternative to a degree and should be
featured. No age minimum and **no IELTS requirement** are published for this award.

---

## 7. Career outcomes

No destinations, salary data or employment rates. The overview names aspiring **Data
Scientists, Data Analysts and Artificial Intelligence specialists** as the intended
audience — that is a statement of who it is *for*, not evidence of outcomes. It may be
reported as Qualifi's stated audience, never as destination data.

---

## 8. Duration and fees

Neither published. No TQT.

---

## 9. Notes

- The 2020 specification date makes this one of the older documents in the Qualifi set.
  In a field where tooling moves quickly, that is worth the reader knowing — state the
  date factually without speculating about currency.
- Only Qualifi page encountered that names programming languages.
- No overlap with other tracker rows; the nearest neighbour is S.No 65 (Level 7 IT).

---

## 10. Declared gaps

| ID | Gap |
|---|---|
| GAP-SOURCE-QF-01 | No fee published |
| GAP-SOURCE-QF-03 | No duration and no TQT published |
| GAP-SOURCE-QF-04 | No career destination or salary data published |
| **GAP-SOURCE-QF-31** | **Master's route: dissertation-only at an unnamed "University partnership". Not a master's degree; no partner named; university decides** |
| **GAP-SOURCE-QF-32** | **Overview calls it a "Postgraduate Diploma"; the official title does not. Use the official title** |
| **GAP-SOURCE-QF-33** | **Two unit lines each appear to contain two titles, so five bullets probably represent seven units. Disclose; do not correct** |
