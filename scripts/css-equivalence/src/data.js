/**
 * data.js — sample CMS data for the MBM landing fixture.
 *
 * Values mirror the real settings shape (see app/Filament/Pages/ManageMbaMastersLanding.php
 * and database/settings/*). Content is realistic but synthetic; the CSS equivalence test
 * only cares that the DOM structure + classes + data-attrs match production markup.
 */

export const data = {
  site: { whatsapp_number: '+971 50 123 4567' },

  hero: {
    headline: "Affordable Online MBA & Master's Programs in UAE",
    eyebrow: 'Admissions open — 2026 intake',
    subheading:
      'Study UK- and EU-accredited business degrees online while you work, with UAE-based support, flexible payment and a class that spans 27 countries.',
    cta_primary_label: 'Start your enquiry',
    cta_secondary_label: 'Read the evidence',
    form_title: 'Tell us where you are headed',
    background_image: 'mba-masters-landing/hero-background.jpg',
  },

  trust: {
    label: 'Trust record',
    quote: 'Every number is a person who chose to keep moving.',
    stats: [
      { value: '94%', label: 'Completion rate' },
      { value: '12k+', label: 'Active learners' },
      { value: '40+', label: 'Partner universities' },
      { value: '4.8/5', label: 'Learner rating' },
      { value: '27', label: 'Countries in the class' },
    ],
  },

  overview: {
    label: 'The learning blueprint',
    heading: 'A structured route, Designed for Working Professionals',
    intro:
      'One system, five foundations: live cohorts, assessed modules, specialisation depth, career movement and a network that outlasts the certificate.',
    cta_primary_label: 'Explore the MBA',
    cta_secondary_label: 'See Master’s programmes',
    items: [
      { title: 'Live cohorts', text: 'Fixed study windows with live seminars in your time zone.' },
      { title: 'Assessed modules', text: 'Each module ends with assessed work that counts toward the degree.', },
      { title: 'Specialisation depth', text: 'UL:Choose a track and go deeper than a generalist degree allows.' },
      { title: 'Career movement', text: 'Progression dossiers map your role before and after the programme.' },
      { title: 'Global network', text: 'A class spread across 27 countries with alumni in 40+ firms.' },
    ],
    plate_image: 'mba-masters-landing/overview-plate.jpg',
  },

  why: {
    label: 'Why Maverick',
    heading: 'Built around the way you actually study',
    intro: 'Four reasons working professionals pick this route over a traditional campus year.',
    chapters: [
      {
        title: 'Flexible study schedule',
        text: 'UL:Study around a full-time job. Live sessions are recorded, deadlines are fixed but humane, and the pace is set for working adults.',
      },
      {
        title: 'Academic rigour',
        text: 'Assessed modules, external marking and recognised awarding bodies — not a course completion certificate.',
      },
      {
        title: 'Specialisation depth',
        text: 'UL:Go deep in finance, marketing, logistics or leadership instead of a one-size-fits-all MBA.',
      },
      {
        title: 'International reach',
        text: 'Study from the UAE with UK and EU accredited degrees, and a class that is already global.',
      },
    ],
  },

  mba: {
    label: 'MBA specializations',
    heading: 'Choose the MBA that fits your move',
    intro: 'Every university route is mapped to its specialisations — pick the track, then the university.',
    tabs: [
      {
        label: 'Rushford route',
        key: 'rbs-mba',
        universities: [
          {
            name: 'Rushford Business School',
            logo: 'mba-masters-landing/mba/logos/rushford.png',
            programs: [
              { title: 'MBA in Finance' },
              { title: 'MBA in Marketing' },
              { title: 'MBA in Human Resources' },
              { title: 'MBA in Logistics & Supply Chain' },
              { title: 'MBA in Leadership' },
              { title: 'MBA in Entrepreneurship' },
              { title: 'MBA in Operations' },
              { title: 'MBA in Digital Business' },
              { title: 'MBA in Project Management' },
              { title: 'MBA in International Management' },
            ],
          },
        ],
      },
      {
        label: 'GAU route',
        key: 'gau-mba',
        universities: [
          {
            name: 'Girne American University',
            logo: 'mba-masters-landing/mba/logos/gau.png',
            programs: [
              { title: 'MBA in Business Management' },
              { title: 'MBA in Finance' },
              { title: 'MBA in Marketing' },
              { title: 'MBA in Human Resource Management' },
              { title: 'MBA in Supply Chain Management' },
              { title: 'MBA in Tourism Management' },
              { title: 'MBA in Real Estate Management' },
              { title: 'MBA in E-Business' },
            ],
          },
          {
            name: 'Rushford Business School',
            logo: 'mba-masters-landing/mba/logos/rushford.png',
            programs: [
              { title: 'MBA in Global Management' },
              { title: 'MBA in Entrepreneurship' },
              { title: 'MBA in Digital Business' },
            ],
          },
        ],
      },
      {
        label: 'Executive route',
        key: 'gau-emba',
        universities: [
          {
            name: 'Girne American University',
            logo: 'mba-masters-landing/mba/logos/gau.png',
            programs: [
              { title: 'Global MBA' },
              { title: 'Master of Business Administration (MBA)' },
              { title: 'Executive MBA in Strategy' },
            ],
          },
        ],
      },
    ],
  },

  masters: {
    label: 'Programme directory',
    heading: "Master's Programs",
    intro: 'Every Master’s programme available through the Maverick network, in one ledger.',
    universities: [
      {
        programs: [
          { title: 'Master in International Business' },
          { title: 'Master in Financial Management' },
          { title: 'Master in Human Resource Management' },
          { title: 'Master in Marketing' },
        ],
      },
      {
        programs: [
          { title: 'Master in Supply Chain Management' },
          { title: 'Master in Project Management' },
          { title: 'Master in Digital Marketing' },
          { title: 'Master in Business Analytics' },
          { title: 'Master in International Business' },
        ],
      },
      {
        programs: [
          { title: 'Master in Tourism Management' },
          { title: 'Master in Law (LLM)' },
          { title: 'Master in Education' },
          { title: 'Master in Cyber Security' },
        ],
      },
    ],
    stage_image: 'mba-masters-landing/masters-stage.jpg',
  },

  class: {
    label: 'Class profile',
    heading: 'Who you will study beside',
    intro: 'A working-professional cohort — the mix below is the class, not a brochure.',
    audience: 'Working professionals from 27 countries',
    metrics: [
      { value: '60+', label: 'Students per cohort' },
      { value: '40+', label: 'Industries represented' },
      { value: '12 yrs', label: 'Average experience' },
      { value: '27', label: 'Countries in the class' },
    ],
  },

  fees: {
    label: 'Fees & payment',
    heading: 'Transparent fees, flexible routes',
    intro: 'What each route costs and how it is paid. Indicative figures are flagged.',
    note: 'Indicative fees exclude university fees where applicable. The admissions team confirms the current fee, VAT and payment terms in writing before you commit.',
    cta_primary_label: 'Request a fee breakdown',
    cta_secondary_label: 'Ask about instalments',
    rows: [
      { program: 'MBA (12 months)', duration: '12 months', mode: 'Online · live cohorts', fee: 'AED 38,000', payment: '3 instalments' },
      { program: 'Executive MBA', duration: '18 months', mode: 'Online · part-time', fee: 'On request', payment: 'Advisor-assisted plan' },
      { program: "Master's (18 months)", duration: '18 months', mode: 'Online · live cohorts', fee: 'AED 46,500', payment: 'Monthly plan' },
      { program: 'Global MBA', duration: '12 months', mode: 'Online · blended', fee: 'Route-specific *', payment: 'Instalments' },
    ],
  },

  career: {
    label: 'Career progression',
    heading: 'Where the class is headed',
    intro: 'Three researched direction dossiers — the moves the class is actually making.',
    stories: [
      {
        name: 'Operations → Supply Chain leadership',
        country: 'UAE',
        program: 'MBA in Logistics & Supply Chain',
        previous_role: 'Operations coordinator, 6 years',
        current_role: 'Supply chain manager',
        quote: 'The logistics track gave me the vocabulary to move from coordination to ownership.',
        portrait: 'mba-masters-landing/career/portraits/ops.jpg',
      },
      {
        name: 'Banking → Financial management',
        country: 'India',
        program: 'MBA in Finance',
        previous_role: 'Credit analyst, 8 years',
        current_role: 'Financial controller',
        quote: 'Assessed modules meant the case work was real — I used two in my promotion review.',
        portrait: 'mba-masters-landing/career/portraits/bank.jpg',
      },
      {
        name: 'Hospitality → International management',
        country: 'UAE',
        program: 'Master in International Business',
        previous_role: 'Hotel operations manager, 5 years',
        current_role: 'Area operations director',
        quote: 'I needed a credential that travels with me, not just a local one.',
        portrait: 'mba-masters-landing/career/portraits/hotel.jpg',
      },
    ],
  },

  alumni: {
    label: 'Employer proof',
    heading: 'Where alumni already work',
    intro: 'A moving ribbon of the employers our graduates have joined.',
    trust_line: 'Logos are shown with employer permission; individual placements verified at programme exit.',
  },

  learning: {
    label: 'Learning experience',
    heading: 'A study system, not a video shelf',
    intro: 'Four parts of the learning experience that make online a method, not a compromise.',
    plate_caption: 'Live cohorts, recorded on demand',
    cta_primary_label: 'See a sample week',
    cta_secondary_label: 'Talk to a current student',
    points: [
      { title: 'Live first, recorded always', text: 'UL:Every seminar runs live for your region and is recorded within the hour.' },
      { title: 'Assessed, not just attended', text: 'Module work is externally marked and counted toward the degree.' },
      { title: 'Support that answers', text: 'A named academic advisor responds within one working day.' },
      { title: 'A route, not a scramble', text: 'OL:Each programme is a fixed sequence of modules with published deadlines.' },
    ],
    plate_image: 'mba-masters-landing/learning-plate.jpg',
  },

  partners: {
    label: 'University partners',
    heading: 'The universities behind the degrees',
    intro: 'Maverick structures the study experience; the degree comes from the university.',
    trust_line: 'Partner list reflects the current 2026 intake catalogue.',
  },

  testimonials: {
    label: 'Learner voices',
    heading: 'What the class says',
    intro: 'Three learners, three stages of the same route.',
    items: [
      {
        name: 'Ayesha K.',
        role: 'MBA in Finance · UAE',
        quote: 'I studied through a promotion and a relocation. The schedule bent around my life, not the other way round.',
        photo: 'mba-masters-landing/testimonials/ayesha.jpg',
      },
      {
        name: 'Rahul M.',
        role: 'MBA in Supply Chain · India',
        quote: 'The assessed case work was the difference. My manager knew what I was actually doing every month.',
        photo: 'mba-masters-landing/testimonials/rahul.jpg',
      },
      {
        name: 'Lina S.',
        role: "Master's in International Business · UAE",
        quote: 'The cohort felt like a peer network from week one. I still trade leads with that group weekly.',
      },
    ],
  },

  faq: {
    label: 'Field notes',
    heading: 'Questions, answered plainly',
    items: [
      {
        question: 'Is the degree recognised for employment in the UAE?',
        answer: 'The awarding bodies behind each programme are recognised in the UAE and the UK. Your offer letter names the awarding body and the recognition reference.',
      },
      {
        question: 'How many hours per week does the programme take?',
        answer: 'Plan for 10–15 hours a week across live sessions, reading and assessed work. The calendar is published before you start.',
      },
      {
        question: 'Can I pay in instalments?',
        answer: 'Yes. Most routes support 3-instalment or monthly plans; the exact schedule is confirmed in writing before you commit.',
      },
      {
        question: 'What happens if I fall behind a module?',
        answer: 'You can defer one module within the programme window at no cost. Your academic advisor sets the recovery plan with you.',
      },
      {
        question: 'Do I get a physical certificate?',
        answer: 'The university issues the certificate in its standard format; Maverick coordinates the application and delivery timeline.',
      },
    ],
  },

  final: {
    label: 'Admissions',
    heading: 'Your next move starts here',
    intro: 'UL:Tell us where you are and where you want to be. An admissions advisor replies within one working day with the route, fee and next steps in writing.',
    cta_primary_label: 'Start your enquiry',
    cta_secondary_label: 'Download the prospectus',
    show_form: true,
    form_title: 'Start your enquiry',
    plate_image: 'mba-masters-landing/final-plate.jpg',
  },

  homepageChrome: {
    accred_label: 'Accredited network',
    accred_heading_line1: 'Accredited',
    accred_heading_line2: '& recognised',
    accred_subtitle: 'Awards from UK and EU institutions, recognised across the region.',
    accred_trust: 'Accreditation references are available in writing from the admissions team.',
  },

  accreditationLogos: [
    { name: 'University of Suffolk', logo_url: 'accreditations/ua.png' },
    { name: 'BICS', logo_url: 'accreditations/bics.png' },
    { name: 'AACSB', logo_url: 'accreditations/aacsb.png' },
    { name: 'EFMD', logo_url: 'accreditations/efmd.png' },
    { name: 'ACCE', logo_url: 'accreditations/acce.png' },
    { name: 'ANZQA', logo_url: 'accreditations/anzqa.png' },
    { name: 'CPE', logo_url: 'accreditations/cpe.png' },
    { name: 'SBBE', logo_url: 'accreditations/sbbe.png' },
  ],

  // empty → partner section uses its hardcoded listing universities
  universityPartnerLogos: [],
  // empty → testimonials fall back to settings items
  storyTestimonials: [],
  // empty → alumni falls back to bundled logos
  alumniLogos: [],
};
