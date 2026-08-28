<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $keys = [
            'numbers.label' => 'Maverick',
            'who_we_are.label' => 'Who We Are',
            'what_is_maverick.label' => 'The Maverick Impact',
            'what_we_do.label' => 'What We Do',
            'how_we_do_it.label' => 'How We Do It',
            'why_maverick.label' => 'Why Maverick',
            'final_cta.label' => 'Take The Next Step',
            'ceo.label' => 'Leadership Message',
            'ceo.heading_line1' => 'A Message from',
            'ceo.heading_line2' => 'Our Founder & CEO',
            'global_opportunities.label' => 'Beyond Borders',
            'global_opportunities.coming_soon_label' => 'Coming Soon',

            'ask_quotient.label' => 'The Maverick Framework',
            'ask_quotient.heading' => 'The ASK Quotient',
            'ask_quotient.description' => '<p>Not a score. A standard for how you show up, what you can do, and how deeply you understand the world around you.</p>',
            'ask_quotient.card_a_letter' => 'A',
            'ask_quotient.card_a_heading' => 'Attitude',
            'ask_quotient.card_a_keywords' => 'Curiosity · Ownership · Courage',
            'ask_quotient.card_a_definition' => '<p>The mindset to stay curious, take ownership, and move with intent—even when the answer is still taking shape.</p>',
            'ask_quotient.card_s_letter' => 'S',
            'ask_quotient.card_s_heading' => 'Skills',
            'ask_quotient.card_s_keywords' => 'Thinking · Communication · Execution',
            'ask_quotient.card_s_definition' => '<p>The ability to turn clear thinking into useful action through precise communication, collaboration, and decisive execution.</p>',
            'ask_quotient.card_k_letter' => 'K',
            'ask_quotient.card_k_heading' => 'Knowledge',
            'ask_quotient.card_k_keywords' => 'Context · Perspective · Judgment',
            'ask_quotient.card_k_definition' => '<p>The context to read complexity, connect what matters, and make decisions with informed conviction.</p>',

            'dei_matrix.label' => 'The Maverick Principle',
            'dei_matrix.heading' => 'The DEI Matrix',
            'dei_matrix.description' => '<p>At Maverick, difference is not a footnote to progress. It is part of how better questions are asked, fairer opportunities are built, and stronger communities take shape.</p>',
            'dei_matrix.row_d_letter' => 'D',
            'dei_matrix.row_d_heading' => 'Diversity',
            'dei_matrix.row_d_definition' => '<p>We value the experiences, identities, and perspectives that make a room more thoughtful—and the work more inventive.</p>',
            'dei_matrix.row_d_practice' => 'Make difference visible.',
            'dei_matrix.row_e_letter' => 'E',
            'dei_matrix.row_e_heading' => 'Equity',
            'dei_matrix.row_e_definition' => '<p>We look beyond one-size-fits-all pathways to make learning, support, and opportunity more considered and more reachable.</p>',
            'dei_matrix.row_e_practice' => 'Design access with intent.',
            'dei_matrix.row_i_letter' => 'I',
            'dei_matrix.row_i_heading' => 'Inclusion',
            'dei_matrix.row_i_definition' => '<p>We create space for people to contribute with confidence, be heard with respect, and help shape what comes next.</p>',
            'dei_matrix.row_i_practice' => 'Turn belonging into action.',

            'homepage_chrome.featured_label' => 'Programs',
            'homepage_chrome.featured_heading_line1' => 'Most In-Demand',
            'homepage_chrome.featured_heading_line2' => 'Programs',
            'homepage_chrome.featured_subtitle' => 'Industry-aligned qualifications designed to accelerate global careers',
            'homepage_chrome.featured_cta_label' => 'Learn More',
            'homepage_chrome.alumni_label' => 'Our Network',
            'homepage_chrome.alumni_heading' => 'Diverse',
            'homepage_chrome.alumni_heading_accent' => 'Alumni',
            'homepage_chrome.alumni_subtitle' => 'Join professionals across leading organizations worldwide',
            'homepage_chrome.alumni_description' => 'Graduates placing at world-class organisations across aviation, energy, finance and government',
            'homepage_chrome.alumni_trust' => 'Join our growing global network of industry leaders',
            'homepage_chrome.accred_label' => 'Trust & Excellence',
            'homepage_chrome.accred_heading_line1' => 'Accreditations &',
            'homepage_chrome.accred_heading_line2' => 'Recognitions',
            'homepage_chrome.accred_subtitle' => 'Globally recognized and strategically partnered with leading institutions worldwide',
            'homepage_chrome.accred_trust' => 'Trusted by global organizations and recognized by leading industry bodies',
            'homepage_chrome.faculty_label' => 'Faculty Voice',
            'homepage_chrome.faculty_heading_line1' => 'Insights From',
            'homepage_chrome.faculty_heading_line2' => 'Industry Experts',
            'homepage_chrome.faculty_subtitle' => 'Real-world perspectives from the minds shaping global business education',
            'homepage_chrome.events_label' => 'Upcoming Events',
            'homepage_chrome.events_heading_line1' => 'Learn Beyond',
            'homepage_chrome.events_heading_line2' => 'The Classroom',
            'homepage_chrome.events_subtitle' => 'Workshops, masterclasses, and graduation moments — join our global learning community',
            'homepage_chrome.testimonials_label' => 'Testimonials',
            'homepage_chrome.testimonials_heading_line1' => 'Stories That',
            'homepage_chrome.testimonials_heading_line2' => 'Inspire',
            'homepage_chrome.testimonials_subtitle' => 'Real voices, real transformations hear from our community',
            'homepage_chrome.faq_label' => 'FAQ',
            'homepage_chrome.faq_heading_line1' => 'Your Questions',
            'homepage_chrome.faq_heading_line2' => 'Answered',
            'homepage_chrome.faq_subtitle' => 'Everything you need to know before beginning your Maverick journey.',
            'homepage_chrome.faq_image_url' => null,
            'homepage_chrome.faq_image_url_asset_id' => null,
        ];

        foreach ($keys as $key => $default) {
            if (! $this->migrator->exists($key)) {
                $this->migrator->add($key, $default);
            }
        }
    }
};
