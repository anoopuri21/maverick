<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // ─── Events Page ───
        $this->migrator->add('events_page.hero_tag', 'Upcoming Events');
        $this->migrator->add('events_page.hero_heading', 'Discover Our');
        $this->migrator->add('events_page.hero_heading_italic', 'Events');
        $this->migrator->add('events_page.hero_description', 'Webinars, workshops and masterclasses designed to keep you learning, connected and ahead.');
        $this->migrator->add('events_page.hero_background_image', 'https://images.pexels.com/photos/2774556/pexels-photo-2774556.jpeg?auto=compress&cs=tinysrgb&w=1920');
        $this->migrator->add('events_page.hero_background_image_asset_id', null);
        $this->migrator->add('events_page.section_label', 'What\'s On');
        $this->migrator->add('events_page.section_heading', 'Upcoming');
        $this->migrator->add('events_page.section_heading_italic', 'Events');
        $this->migrator->add('events_page.section_subheading', 'Save the date — opportunities to learn, connect and grow with the Maverick community.');

        // ─── Student Success Page ───
        $this->migrator->add('student_success_page.hero_tag', 'Student Success');
        $this->migrator->add('student_success_page.hero_heading', 'Real Stories, Real');
        $this->migrator->add('student_success_page.hero_heading_italic', 'Impact');
        $this->migrator->add('student_success_page.hero_description', 'The journeys and achievements of our students and graduates around the world.');
        $this->migrator->add('student_success_page.hero_background_image', 'https://images.pexels.com/photos/3183150/pexels-photo-3183150.jpeg?auto=compress&cs=tinysrgb&w=1920');
        $this->migrator->add('student_success_page.hero_background_image_asset_id', null);
        $this->migrator->add('student_success_page.section_label', 'Success Stories');
        $this->migrator->add('student_success_page.section_heading', 'Students Who');
        $this->migrator->add('student_success_page.section_heading_italic', 'Made It');
        $this->migrator->add('student_success_page.section_subheading', 'Hear from our graduates — where they came from, and where their Maverick qualification is taking them.');
        $this->migrator->add('student_success_page.stories', [
            ['name' => 'Rahul S.', 'role' => 'Business Manager · UAE', 'quote' => 'The programme structure and support helped me balance work with study seamlessly.', 'stars' => 5],
            ['name' => 'Fatima A.', 'role' => 'Marketing Executive · UAE', 'quote' => 'The flexible learning approach let me grow my career while I studied.', 'stars' => 5],
            ['name' => 'Mohammed K.', 'role' => 'Entrepreneur · UAE', 'quote' => 'A solid international pathway with very helpful advisors along the way.', 'stars' => 4],
            ['name' => 'Priya N.', 'role' => 'Business Analyst · India', 'quote' => 'Globally recognised and career-focused — exactly what I needed.', 'stars' => 5],
            ['name' => 'Daniel O.', 'role' => 'Operations Manager · UK', 'quote' => 'Exceptional faculty and a truly international learning environment.', 'stars' => 5],
            ['name' => 'Sara E.', 'role' => 'Project Coordinator · UAE', 'quote' => 'The mentorship and support transformed my professional confidence.', 'stars' => 5],
        ]);

        // ─── Contact Page ───
        $this->migrator->add('contact_page.eyebrow', 'Contact Us');
        $this->migrator->add('contact_page.heading', 'Let\'s Start a Conversation');
        $this->migrator->add('contact_page.description', 'Whether you are exploring our executive postgraduate pathways, seeking a corporate partnership, or require technical admissions support, our advisory team is here to assist you.');
        $this->migrator->add('contact_page.label_address', 'Campus Location');
        $this->migrator->add('contact_page.label_email', 'Email Inquiry');
        $this->migrator->add('contact_page.label_phone', 'Admissions & Hotlines');
        $this->migrator->add('contact_page.label_hours', 'Office Hours');
        $this->migrator->add('contact_page.label_social', 'Follow Our Insights');
        $this->migrator->add('contact_page.form_title', 'Send Us a Message');
        $this->migrator->add('contact_page.form_subtitle', 'Fill in the fields below, and our program directors will respond to you within 24 hours.');
        $this->migrator->add('contact_page.success_message', 'Thank you! We\'ll get back to you within 24 hours.');

        // ─── Programs Listing Page ───
        $this->migrator->add('programs_listing_page.hero_tag', 'MAVERICK PROGRAMMES');
        $this->migrator->add('programs_listing_page.hero_heading', 'Explore Your');
        $this->migrator->add('programs_listing_page.hero_heading_italic', 'Programme');
        $this->migrator->add('programs_listing_page.hero_description', 'Globally recognised qualifications designed to move your career forward.');
        $this->migrator->add('programs_listing_page.hero_background_image', null);
        $this->migrator->add('programs_listing_page.hero_background_image_asset_id', null);
        $this->migrator->add('programs_listing_page.cta_label', 'Browse Programmes');
        $this->migrator->add('programs_listing_page.empty_message', 'Programmes coming soon.');
        $this->migrator->add('programs_listing_page.card_cta_label', 'View Programme');

        // ─── Faculty Voice Page ───
        $this->migrator->add('faculty_voice_page.hero_tag', 'Faculty Voice');
        $this->migrator->add('faculty_voice_page.hero_heading', 'Insights from');
        $this->migrator->add('faculty_voice_page.hero_heading_italic', 'industry experts');
        $this->migrator->add('faculty_voice_page.hero_description', 'Real-world perspectives from the minds shaping global business education.');
        $this->migrator->add('faculty_voice_page.hero_background_image', null);
        $this->migrator->add('faculty_voice_page.hero_background_image_asset_id', null);
        $this->migrator->add('faculty_voice_page.empty_message', 'Faculty voices coming soon.');

        // ─── Accreditations Page ───
        $this->migrator->add('accreditations_page.hero_tag', 'ACCREDITATIONS & RECOGNITIONS');
        $this->migrator->add('accreditations_page.hero_heading_line1', 'Globally Recognised,');
        $this->migrator->add('accreditations_page.hero_heading_italic', 'Locally Trusted');
        $this->migrator->add('accreditations_page.hero_description', 'Our commitment to excellence is validated by the world\'s most respected accreditation bodies, regulatory authorities, and industry partners. Every credential represents our dedication to quality.');
        $this->migrator->add('accreditations_page.hero_background_image', 'https://images.pexels.com/photos/267885/pexels-photo-267885.jpeg?auto=compress&cs=tinysrgb&w=1920');
        $this->migrator->add('accreditations_page.hero_background_image_asset_id', null);
        $this->migrator->add('accreditations_page.credentials_label', 'Our Credentials');
        $this->migrator->add('accreditations_page.credentials_heading', 'Accreditations');
        $this->migrator->add('accreditations_page.credentials_heading_span', '& Recognition');
        $this->migrator->add('accreditations_page.credentials_subtitle', 'We partner with leading universities and hold accreditations from globally respected bodies.');
        $this->migrator->add('accreditations_page.awards_label', 'Achievements');
        $this->migrator->add('accreditations_page.awards_heading', 'Awards');
        $this->migrator->add('accreditations_page.awards_heading_span', '& Achievements');
        $this->migrator->add('accreditations_page.awards_subtitle', 'Our commitment to excellence has been recognised by leading education bodies worldwide.');

        // ─── Media Gallery Page ───
        $this->migrator->add('media_gallery_page.hero_tag', 'MEDIA GALLERY');
        $this->migrator->add('media_gallery_page.hero_heading_line1', 'Life at Maverick,');
        $this->migrator->add('media_gallery_page.hero_heading_italic', 'In Pictures');
        $this->migrator->add('media_gallery_page.hero_description', 'Explore the moments that define our community — from graduation celebrations and campus life to global events and media spotlight. Every image tells a story of ambition, achievement, and the transformative power of education.');
        $this->migrator->add('media_gallery_page.hero_background_image', 'https://images.pexels.com/photos/1181406/pexels-photo-1181406.jpeg?auto=compress&cs=tinysrgb&w=1920');
        $this->migrator->add('media_gallery_page.hero_background_image_asset_id', null);
        $this->migrator->add('media_gallery_page.photos_label', 'GALLERY');
        $this->migrator->add('media_gallery_page.photos_heading', 'Moments');
        $this->migrator->add('media_gallery_page.photos_subheading', 'A living collection of our community — step into the full-screen lightbox.');
        $this->migrator->add('media_gallery_page.videos_label', 'WATCH');
        $this->migrator->add('media_gallery_page.videos_heading', 'Featured');
        $this->migrator->add('media_gallery_page.videos_subheading', 'Relive our proudest moments through the lens — from graduation days to executive forums.');

        // ─── Programs Detail Chrome ───
        $this->migrator->add('programs_detail_chrome.enquire_label', 'Enquire Now');
        $this->migrator->add('programs_detail_chrome.apply_label', 'Apply Now');
        $this->migrator->add('programs_detail_chrome.scholarship_badge', 'Scholarship Available');
        $this->migrator->add('programs_detail_chrome.download_brochure_label', 'Download Brochure');
        $this->migrator->add('programs_detail_chrome.quick_highlights_label', 'Quick Highlights');
        $this->migrator->add('programs_detail_chrome.glance_heading', 'Programme at a Glance');
        $this->migrator->add('programs_detail_chrome.overview_label', 'Programme Overview');
        $this->migrator->add('programs_detail_chrome.overview_heading', 'About this Programme');
        $this->migrator->add('programs_detail_chrome.why_label', 'Why Choose');
        $this->migrator->add('programs_detail_chrome.why_heading', 'Why Choose This Programme?');
        $this->migrator->add('programs_detail_chrome.learn_label', 'What You\'ll Learn');
        $this->migrator->add('programs_detail_chrome.learn_heading', 'Learning Outcomes');
        $this->migrator->add('programs_detail_chrome.learn_intro', 'Students will learn to:');
        $this->migrator->add('programs_detail_chrome.career_label', 'Career Opportunities');
        $this->migrator->add('programs_detail_chrome.career_heading', 'Where This Degree Can Take You');
        $this->migrator->add('programs_detail_chrome.career_intro', 'Potential careers include:');
        $this->migrator->add('programs_detail_chrome.structure_label', 'Programme Structure');
        $this->migrator->add('programs_detail_chrome.structure_heading', 'Your Journey, Year by Year');
        $this->migrator->add('programs_detail_chrome.structure_intro', 'A structured curriculum that builds from foundations through to advanced study.');
        $this->migrator->add('programs_detail_chrome.university_label', 'The University');
        $this->migrator->add('programs_detail_chrome.university_heading', 'A Globally Connected University');
        $this->migrator->add('programs_detail_chrome.accreditation_label', 'Accreditation');
        $this->migrator->add('programs_detail_chrome.accreditation_heading', 'Accreditation & Recognition');
        $this->migrator->add('programs_detail_chrome.partner_label', 'Your Learning Partner');
        $this->migrator->add('programs_detail_chrome.partner_heading', 'Why Study Through Maverick?');
        $this->migrator->add('programs_detail_chrome.partner_intro', 'Students receive:');
        $this->migrator->add('programs_detail_chrome.stories_label', 'Stories');
        $this->migrator->add('programs_detail_chrome.stories_heading', 'Student Success Stories');
        $this->migrator->add('programs_detail_chrome.fees_intro', 'Fee structure varies by intake and study mode. Select any option to receive the full details.');
        $this->migrator->add('programs_detail_chrome.fees_request_label', 'Request the full fee structure');
        $this->migrator->add('programs_detail_chrome.faq_heading', 'Frequently Asked Questions');
        $this->migrator->add('programs_detail_chrome.enquiry_heading', 'Enquire About This Programme');
        $this->migrator->add('programs_detail_chrome.enquiry_subheading', 'Share a few details and our admissions team will reach out to guide you through the next steps.');
        $this->migrator->add('programs_detail_chrome.final_cta_heading', 'Ready to Begin Your Journey?');
        $this->migrator->add('programs_detail_chrome.final_cta_body', 'Speak to our admissions team today and take the first step toward a globally recognised degree.');

        // ─── Pathway Programs Pathways Section ───
        $this->migrator->add('pathway_programs.pathways_label', 'Our Pathways');
        $this->migrator->add('pathway_programs.pathways_heading', 'Choose the pathway');
        $this->migrator->add('pathway_programs.pathways_heading_italic', 'that fits your goals');
        $this->migrator->add('pathway_programs.pathways_cta_label', 'Explore Programme');
        $this->migrator->add('pathway_programs.pathways_empty_message', 'Pathway programmes will be listed here soon. Please check back shortly.');
    }
};