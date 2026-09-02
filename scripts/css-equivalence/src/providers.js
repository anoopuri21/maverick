/**
 * providers.js — per-partial derived-variable providers.
 *
 * Each partial's @php block computes derived variables. Instead of interpreting PHP,
 * we re-implement that (small, bounded) logic in JS here. The compiler calls the
 * provider for the current partial whenever it hits an @php block, with the current
 * scope (which includes loop variables like $stat, $row, $uni).
 */

import { Collection } from './blade-lite.js';

const D = (x) => x.data;

// PHP: trim; if '' or 'Global MBA' or 'Master of Business Administration (MBA)' → null
// else strip /^(?:Executive\s+)?MBA\s+in\s+/i prefix (keep original if no match), trim
function toSpecialization(program) {
  const title = String(program?.title ?? '').trim();
  if (title === '' || title.toLowerCase() === 'global mba' || title.toLowerCase() === 'master of business administration (mba)') {
    return null;
  }
  const stripped = title.replace(/^(?:Executive\s+)?MBA\s+in\s+/i, '');
  return { title: (stripped || title).trim() };
}

const CHAPTER_ICONS = [
  ['flexible', 'calendar'],
  ['academic', 'graduation-cap'],
  ['specialization', 'compass'],
  ['payment', 'wallet'],
  ['international', 'globe'],
  ['community', 'users'],
];

export const providers = {
  hero(ctx) {
    const hero = D(ctx).hero;
    const headline = hero.headline;
    let line1 = headline;
    let line2 = null;
    if (headline.includes(' & ')) {
      const [a, b] = headline.split(' & ');
      line1 = a;
      line2 = `& ${b}`;
    } else {
      const m = headline.match(/^(.+?\s(?:MBA|Master's|Masters))\s+(.+)$/i);
      if (m) {
        line1 = m[1];
        line2 = m[2];
      }
    }
    const bg = hero.background_image
      ? `/${hero.background_image}`
      : '/assets/images/edutainment/hero-cinematic.jpg';
    return { bg, headline, line1, line2 };
  },

  trust(ctx) {
    const t = D(ctx).trust;
    const vars = {
      stats: new Collection((t.stats ?? []).filter((s) => s.value || s.label)),
      heading: t.label || 'Trusted by learners across the UAE & beyond',
      quote: t.quote || 'Every number is a person who chose to keep moving.',
    };
    const stat = ctx.scope.stat;
    if (stat) {
      const rawValue = String(stat.value ?? '');
      vars.rawValue = rawValue;
      vars.numericValue = rawValue.replace(/[^\d.]/g, '');
      vars.suffix = rawValue.replace(/[\d.,\s]/g, '');
    }
    return vars;
  },

  overview(ctx) {
    const o = D(ctx).overview;
    const overviewHeading = String(o.heading ?? '');
    const overviewHeadingAccent = 'Designed for Working Professionals';
    const hasOverviewHeadingAccent = overviewHeading.includes(overviewHeadingAccent);
    const vars = {
      items: new Collection(o.items),
      plate: o.plate_image ? `/${o.plate_image}` : '/assets/images/homepage/mba-management.jpg',
      hasCtas: Boolean(o.cta_primary_label) || Boolean(o.cta_secondary_label),
      overviewHeading,
      overviewHeadingAccent,
      hasOverviewHeadingAccent,
    };
    if (hasOverviewHeadingAccent) {
      const [lead, tail] = overviewHeading.split(overviewHeadingAccent);
      vars.overviewHeadingLead = lead;
      vars.overviewHeadingTail = tail ?? '';
    }
    return vars;
  },

  why(ctx) {
    const w = D(ctx).why;
    const vars = { chapters: new Collection(w.chapters) };
    const chapter = ctx.scope.chapter;
    const i = ctx.scope.i;
    if (chapter !== undefined && i !== undefined) {
      vars.tone = i % 2 === 0 ? 'void' : 'paper';
      const titleKey = String(chapter.title ?? '').trim().toLowerCase();
      let icon = 'compass';
      for (const [keyword, iconName] of CHAPTER_ICONS) {
        if (titleKey.includes(keyword)) {
          icon = iconName;
          break;
        }
      }
      vars.icon = icon;
    }
    return vars;
  },

  mba(ctx) {
    const m = D(ctx).mba;
    const generatedImageBase = 'assets/images/mba-masters-landing/mba/';
    const generatedStage = `${generatedImageBase}mba-stage.jpg`;
    const generatedImagesByTab = {
      'rbs-mba': [`${generatedImageBase}specialized-mba.jpg`],
      'gau-mba': [`${generatedImageBase}business-management-mba.jpg`],
      'gau-emba': [`${generatedImageBase}executive-mba.jpg`],
      'uca-global-mba': [`${generatedImageBase}global-mba.jpg`],
    };
    const vars = {
      generatedImagesByTab,
      stage: `/${generatedStage}`,
      fallbackCampus: generatedStage,
      specializationOffset: Number(m.specialization_offset ?? 0),
    };

    const tab = ctx.scope.tab;
    if (tab === undefined) {
      // top-level block: build the tabs collection (with specializations mapped)
      vars.tabs = new Collection(
        m.tabs
          .filter((t) => t.label)
          .map((t) => {
            const universities = (t.universities ?? [])
              .map((u) => {
                const specializations = (u.programs ?? [])
                  .map(toSpecialization)
                  .filter(Boolean);
                return { ...u, specializations };
              })
              .filter((u) => u.specializations.length > 0);
            return { ...t, universities };
          })
          .filter((t) => t.universities.length > 0)
      );
    }
    if (tab !== undefined && ctx.scope.uni === undefined && vars.unis === undefined) {
      // per-tab block
      vars.unis = new Collection((tab.universities ?? []).filter((u) => u.name));
    }
    const uni = ctx.scope.uni;
    const ui = ctx.scope.ui;
    if (uni !== undefined && ui !== undefined) {
      // per-uni block
      const logo = uni.logo ? `/${uni.logo}` : '';
      const tabKey = String(tab?.key ?? '').trim().toLowerCase();
      const generatedPhoto = generatedImagesByTab[tabKey]?.[ui] ?? generatedStage;
      const photo = `/${generatedPhoto}`;
      const specializations = new Collection((uni.specializations ?? []).filter((s) => s.title));
      let specializationColumns;
      if (specializations.count() > 8) {
        const size = Math.ceil(specializations.count() / 2);
        specializationColumns = specializations.chunk(size);
      } else {
        specializationColumns = new Collection([specializations]);
      }
      const initials = uni.name
        .split(/\s+/)
        .filter(Boolean)
        .map((w) => w[0].toUpperCase())
        .slice(0, 3)
        .join('');
      vars.logo = logo;
      vars.tabKey = tabKey;
      vars.photo = photo;
      vars.specializations = specializations;
      vars.specializationColumns = specializationColumns;
      vars.initials = initials;
      vars.flip = ui % 2 === 1;
    }
    return vars;
  },

  masters(ctx) {
    const ms = D(ctx).masters;
    const titles = [];
    const seen = new Set();
    for (const uni of ms.universities ?? []) {
      for (const p of uni.programs ?? []) {
        const title = String(p.title ?? '').trim();
        if (title === '') continue;
        const key = title.toLowerCase();
        if (seen.has(key)) continue;
        seen.add(key);
        titles.push(title);
      }
    }
    return {
      programs: new Collection(titles),
      plate: ms.stage_image
        ? `/${ms.stage_image}`
        : '/assets/images/edutainment/dubai-uae-skyline-students-studying-camp-1.jpg',
      heading: ms.heading || "Master's Programs",
      label: ms.label || 'Programme directory',
    };
  },

  'class-snapshot'(ctx) {
    const c = D(ctx).class;
    const snapshotCountries = [
      ['Moldova', 'MD'], ['Hungary', 'HU'], ['Malaysia', 'MY'], ['China', 'CN'],
      ['Maldives', 'MV'], ['Hong Kong', 'HK'], ['Myanmar', 'MM'], ['Australia', 'AU'],
      ['Ghana', 'GH'], ['Nigeria', 'NG'], ['Egypt', 'EG'], ['Syria', 'SY'],
      ['Yemen', 'YE'], ['Romania', 'RO'], ['Congo', 'CG'], ['Saudi Arabia', 'SA'],
      ['UAE', 'AE'], ['India', 'IN'], ['Sri Lanka', 'LK'], ['UK', 'GB'],
      ['USA', 'US'], ['Switzerland', 'CH'], ['Chile', 'CL'], ['Peru', 'PE'],
      ['Uganda', 'UG'], ['Zimbabwe', 'ZW'], ['Vietnam', 'VN'],
    ].map(([name, iso2]) => ({ name, iso2 }));
    return {
      snapshotMetrics: new Collection((c.metrics ?? []).filter((m) => m.value || m.label).slice(0, 4)),
      snapshotCountries,
      metricIcons: ['users-round', 'briefcase', 'calendar', 'users-round'],
    };
  },

  fees(ctx) {
    const f = D(ctx).fees;
    const vars = { rows: new Collection(f.rows.filter((r) => r.program)) };
    const row = ctx.scope.row;
    if (row) {
      let payment = String(row.payment ?? '').trim();
      const fee = String(row.fee ?? '—').trim();
      const feeIsIndicative =
        fee.includes('XX,XXX') || fee.includes('On request') || fee.includes('Route-specific') || fee.includes('*');
      if (payment.toLowerCase().includes('advisor')) payment = 'Details on request';
      vars.payment = payment;
      vars.fee = fee;
      vars.feeIsIndicative = feeIsIndicative;
    }
    return vars;
  },

  career(ctx) {
    const c = D(ctx).career;
    return {
      stories: new Collection((c.stories ?? []).filter((s) => s.name)),
      fallbackPortrait: 'assets/images/homepage/business.jpg',
    };
  },

  alumni() {
    // $alumniLogos empty → fallbackLogos branch
    const fallbackLogos = [
      ['Goldman Sachs', 'assets/images/alumni/alumn-7.png'],
      ['Deloitte', 'assets/images/alumni/alumn-8.png'],
      ['World Bank', 'assets/images/alumni/alumn-9.png'],
      ['DHL', 'assets/images/alumni/alumn-10.png'],
      ['Apple', 'assets/images/alumni/alumn-11.png'],
      ['stc', 'assets/images/alumni/alumn-12.png'],
    ].map(([name, src]) => ({ name, src: `/${src}` }));
    return { logos: new Collection([]), renderLogos: new Collection(fallbackLogos) };
  },

  learning(ctx) {
    const l = D(ctx).learning;
    return {
      points: new Collection((l.points ?? []).filter((p) => p.title)),
      plate: l.plate_image ? `/${l.plate_image}` : '/assets/images/homepage/mba-management.jpg',
      supportingImages: ['assets/images/edutainment/learning-beyond.png', 'assets/images/programs/enquire-seminar.jpg'],
      pointIcons: ['play', 'calendar', 'life-buoy', 'route'],
    };
  },

  partners() {
    // stored empty → listing universities with their hardcoded URLs
    const listingUniversities = [
      ['Rushford Business School', 'https://rushford.ch/wp-content/uploads/2022/12/RUSHFORD-LOGO-COLOR-1.png'],
      ['Girne American University', 'https://www.gau.edu.tr/template/gau/assets/img/logo2_en.png'],
      ['University for the Creative Arts', 'https://www.uca.ac.uk/media/uca-2020/site-assets/media/logos/uca-logo-black.png'],
      ['University of Wolverhampton', 'https://upload.wikimedia.org/wikipedia/en/1/19/University_of_Wolverhampton_logo.jpg'],
    ].map(([name, src]) => ({ name, src }));
    return { storedLogos: new Collection([]), renderLogos: new Collection(listingUniversities) };
  },

  'video-proof'() {
    return {
      videoUrl: 'https://youtu.be/4p0rsCEljgo?si=7FHizEp4gkj6HPU7',
      videoThumbnail: 'https://i.ytimg.com/vi/4p0rsCEljgo/hqdefault.jpg',
      videoEmbedUrl: 'https://www.youtube.com/embed/4p0rsCEljgo?autoplay=1',
    };
  },

  testimonials(ctx) {
    const t = D(ctx).testimonials;
    const items = (t.items ?? [])
      .filter((x) => x.name || x.quote)
      .map((x) => ({
        name: x.name ?? '',
        role: x.role ?? '',
        quote: x.quote ?? '',
        photo: x.photo ? `/${x.photo}` : '',
      }));
    return {
      items: new Collection(items),
      fallbackPhoto: '/assets/images/alumni/alumn-1.png',
    };
  },

  faq(ctx) {
    const f = D(ctx).faq;
    const vars = { items: new Collection((f.items ?? []).filter((i) => i.question)) };
    const fi = ctx.scope.fi;
    if (fi !== undefined) vars.panelId = `mlp-faq-panel-${fi + 1}`;
    return vars;
  },

  final(ctx) {
    const f = D(ctx).final;
    return {
      plate: f.plate_image ? `/${f.plate_image}` : '/assets/images/edutainment/cta-cinematic.jpg',
      showForm: f.show_form !== false,
    };
  },

  accreditations(ctx) {
    const d = D(ctx);
    return {
      accreditationLogos: new Collection(d.accreditationLogos ?? []),
      homepageChrome: d.homepageChrome,
    };
  },

  'enquire-form'() {
    return { formId: 'mlp-enquiry-fixture-uuid-0001' };
  },
};
