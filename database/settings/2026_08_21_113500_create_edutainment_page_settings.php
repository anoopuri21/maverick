<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('edutainment_hero.tag', 'Maverick Edutainment UAE');
        $this->migrator->add('edutainment_hero.heading', 'Maverick Edutainment:');
        $this->migrator->add('edutainment_hero.heading_italic', 'Educational Tours That Bring Learning to Life');
        $this->migrator->add('edutainment_hero.description', 'Educational tours and international study trips for schools, universities and student groups — turning destinations into unforgettable learning environments.');
        $this->migrator->add('edutainment_hero.background_image', 'assets/images/edutainment/hero-cinematic.jpg');
        $this->migrator->add('edutainment_hero.background_image_asset_id', null);

        $this->migrator->add('edutainment_intro.label', 'Educational Tours That Bring Learning to Life');
        $this->migrator->add('edutainment_intro.title_line1', 'Explore the World.');
        $this->migrator->add('edutainment_intro.title_line2', 'Experience');
        $this->migrator->add('edutainment_intro.title_line2_italic', 'New Cultures');
        $this->migrator->add('edutainment_intro.title_line3', 'Learn Beyond the');
        $this->migrator->add('edutainment_intro.title_line3_italic', 'Classroom');
        $this->migrator->add('edutainment_intro.body', '<p>Education does not have to remain inside a classroom. Maverick Edutainment creates educational tours and international study trips that combine learning, exploration, culture and entertainment in one meaningful experience.</p><p>From introducing school students to the innovation, heritage and cultural diversity of the UAE, to taking university learners on an international study tour to China, every programme is designed to turn destinations into learning environments.</p><p>Students do not simply visit a new place. They observe, participate, interact, question and experience what they have previously learned through books, lectures or online classes.</p><p>Whether you represent a school, college, university or educational organisation, Maverick can help you create an age-appropriate educational travel programme aligned with your students, learning objectives and preferred destination.</p>');
        $this->migrator->add('edutainment_intro.emphasis', 'Learning becomes more memorable when students experience it for themselves.');
        $this->migrator->add('edutainment_intro.ctas', [
            ['label' => 'Plan an Educational Tour', 'url' => '/contact', 'style' => 'primary'],
            ['label' => 'Request a Custom Itinerary', 'url' => '/contact', 'style' => 'secondary'],
            ['label' => 'Speak to Our Team', 'url' => '/contact', 'style' => 'outline'],
        ]);

        $this->migrator->add('edutainment_what_is.label', 'Understanding Edutainment');
        $this->migrator->add('edutainment_what_is.title', 'What Is');
        $this->migrator->add('edutainment_what_is.title_italic', 'Edutainment?');
        $this->migrator->add('edutainment_what_is.title_break', false);
        $this->migrator->add('edutainment_what_is.wordmark_line1', 'EDU');
        $this->migrator->add('edutainment_what_is.wordmark_plus', '+');
        $this->migrator->add('edutainment_what_is.wordmark_line2', 'TAINMENT');
        $this->migrator->add('edutainment_what_is.wordmark_sub', 'Education × Entertainment');
        $this->migrator->add('edutainment_what_is.lead', '<p>Edutainment is the combination of <strong>education and entertainment</strong>.</p>');
        $this->migrator->add('edutainment_what_is.body', '<p>At Maverick, Edutainment means creating carefully planned educational journeys in which students learn while exploring new destinations, cultures, industries, institutions and communities.</p><p>Instead of offering a conventional sightseeing trip, we create a structured learning journey where every visit has a purpose. The result is an experience that is informative, engaging, enjoyable and connected to the student\'s personal or academic development.</p>');
        $this->migrator->add('edutainment_what_is.list_title', 'A Maverick Edutainment programme may combine:');
        $this->migrator->add('edutainment_what_is.items', [
            'Educational institution visits',
            'University and campus experiences',
            'Business and industry exposure',
            'Cultural and historical exploration',
            'Innovation and technology visits',
            'Interactive workshops',
            'Leadership and team-building activities',
            'Language and cultural immersion',
            'Recreational experiences',
            'Guided sightseeing',
            'Reflection and knowledge-sharing sessions',
        ]);
        $this->migrator->add('edutainment_what_is.quote', 'Learning becomes more memorable when students experience it for themselves.');

        $this->migrator->add('edutainment_learning_beyond.label', 'Beyond Classroom');
        $this->migrator->add('edutainment_learning_beyond.title', 'Learning Beyond');
        $this->migrator->add('edutainment_learning_beyond.title_line2', 'the');
        $this->migrator->add('edutainment_learning_beyond.title_italic', 'Classroom');
        $this->migrator->add('edutainment_learning_beyond.title_break', true);
        $this->migrator->add('edutainment_learning_beyond.body', '<p>Some lessons are better understood when they are experienced.</p><p>A student can read about a country\'s history, but visiting its heritage sites creates a stronger connection to that history. A business learner can study innovation, but visiting a technology company allows them to see how innovation works in practice.</p><p><strong>Maverick Edutainment helps connect academic ideas with real places, people and experiences.</strong></p>');
        $this->migrator->add('edutainment_learning_beyond.image', 'assets/images/edutainment/learning-beyond.png');
        $this->migrator->add('edutainment_learning_beyond.image_asset_id', null);
        $this->migrator->add('edutainment_learning_beyond.cards_heading', 'Students can return from their journey with:');
        $this->migrator->add('edutainment_learning_beyond.cards', [
            ['icon' => '🌍', 'title' => 'Greater cultural awareness'],
            ['icon' => '✈️', 'title' => 'Wider global exposure'],
            ['icon' => '💪', 'title' => 'Improved confidence and independence'],
            ['icon' => '🗣️', 'title' => 'Stronger communication skills'],
            ['icon' => '📚', 'title' => 'New academic and professional interests'],
            ['icon' => '🏭', 'title' => 'Better understanding of different industries'],
            ['icon' => '👥', 'title' => 'More meaningful relationships with classmates'],
            ['icon' => '⭐', 'title' => 'Memorable experiences connected to learning'],
        ]);

        $this->migrator->add('edutainment_who_for.label', 'Target Audience');
        $this->migrator->add('edutainment_who_for.title', 'Who Are Our Educational Tours');
        $this->migrator->add('edutainment_who_for.title_line2', null);
        $this->migrator->add('edutainment_who_for.title_italic', 'Designed For?');
        $this->migrator->add('edutainment_who_for.title_break', true);
        $this->migrator->add('edutainment_who_for.intro', '<p>Maverick Edutainment programmes can be customised for learners of different ages, backgrounds and educational levels.</p>');
        $this->migrator->add('edutainment_who_for.cards', [
            ['icon_key' => 'graduation-cap', 'title' => 'School Students', 'description' => 'Age-appropriate local and international educational tours combining discovery, cultural learning, interactive activities and entertainment.'],
            ['icon_key' => 'graduation-cap-user', 'title' => 'College and University Students', 'description' => 'Academic study tours that may include university visits, industry exposure, cultural experiences, workshops and interaction with international students.'],
            ['icon_key' => 'monitor', 'title' => 'Business and MBA Students', 'description' => 'International business tours designed to expose learners to companies, innovation hubs, universities, entrepreneurs and different business environments.'],
            ['icon_key' => 'book', 'title' => 'Doctoral and Research Students', 'description' => 'Academic exposure visits that may include universities, research institutions, conferences, educational discussions and cultural experiences.'],
            ['icon_key' => 'building', 'title' => 'Educational Institutions', 'description' => 'Customised group programmes for schools, colleges, universities, training organisations and student associations.'],
            ['icon_key' => 'user-badge', 'title' => 'Professional and Executive Groups', 'description' => 'International learning visits combining business exposure, leadership development, networking and cultural discovery.'],
        ]);
        $this->migrator->add('edutainment_who_for.ctas', [
            ['label' => 'Discuss Your Student Group', 'url' => '/contact', 'style' => 'primary'],
        ]);

        $this->migrator->add('edutainment_programmes.label', 'Our Programmes');
        $this->migrator->add('edutainment_programmes.title', 'Our Edutainment');
        $this->migrator->add('edutainment_programmes.title_line2', null);
        $this->migrator->add('edutainment_programmes.title_italic', 'Programmes');
        $this->migrator->add('edutainment_programmes.title_break', true);
        $this->migrator->add('edutainment_programmes.cards', [
            [
                'image' => 'assets/images/edutainment/dubai-uae-skyline-students-studying-camp-1.jpg',
                'image_asset_id' => null,
                'badge' => 'UAE',
                'title' => 'UAE Educational Tours for School Students',
                'description' => 'Help students discover the UAE through a carefully planned combination of education, culture, innovation and entertainment.',
                'bullets' => [
                    'Emirati heritage and traditions',
                    'UAE history and national development',
                    'Science and technology',
                    'Sustainability and environmental awareness',
                    'Space exploration',
                    'Architecture and engineering',
                ],
                'cta_label' => 'Plan a UAE Student Tour',
                'cta_url' => '/contact',
                'is_featured' => false,
            ],
            [
                'image' => 'assets/images/edutainment/international-students-university-campus-1.jpg',
                'image_asset_id' => null,
                'badge' => 'International',
                'title' => 'International Study Tours',
                'description' => 'Take learning beyond national borders through a structured international educational journey.',
                'bullets' => [
                    'University and campus visits',
                    'Business and company exposure',
                    'Innovation centres',
                    'Cultural landmarks',
                    'Local workshops',
                    'Guided city exploration',
                ],
                'cta_label' => 'Explore International Study Tours',
                'cta_url' => '/contact',
                'is_featured' => false,
            ],
            [
                'image' => 'assets/images/edutainment/great-wall-china-travel-students-busines-2.jpg',
                'image_asset_id' => null,
                'badge' => 'Featured',
                'title' => 'China Educational and Business Study Tour',
                'description' => 'Discover one of the world\'s most influential centres for business, technology, manufacturing, culture and innovation.',
                'bullets' => [],
                'cta_label' => null,
                'cta_url' => null,
                'is_featured' => true,
            ],
        ]);
        $this->migrator->add('edutainment_programmes.china_items', [
            ['icon_key' => 'graduation-cap', 'title' => 'University Exposure', 'description' => 'Visit selected universities and learn about their programmes, campuses, research environment and approach to education.'],
            ['icon_key' => 'briefcase', 'title' => 'Business and Industry Visits', 'description' => 'Explore how organisations operate within sectors such as technology, manufacturing, e-commerce, finance, logistics and AI.'],
            ['icon_key' => 'cpu', 'title' => 'Innovation and Entrepreneurship', 'description' => 'Discover startup ecosystems, technology centres, innovation districts and emerging business models.'],
            ['icon_key' => 'building', 'title' => 'Cultural Immersion', 'description' => 'Experience Chinese history and traditions through cultural sites, local activities, art, food, language and community interaction.'],
            ['icon_key' => 'users', 'title' => 'Student Interaction', 'description' => 'Where available, connect with local or international students and exchange ideas about education, culture and career aspirations.'],
            ['icon_key' => 'star', 'title' => 'Leadership and Global Business Learning', 'description' => 'Participate in discussions, workshops or reflection sessions connected to international business, innovation, leadership and cross-cultural management.'],
        ]);
        $this->migrator->add('edutainment_programmes.china_cta_label', 'Request a China Study Tour Itinerary');
        $this->migrator->add('edutainment_programmes.china_cta_url', '/contact');

        $this->migrator->add('edutainment_themes.label', 'Tour Themes');
        $this->migrator->add('edutainment_themes.title', 'Educational Tour');
        $this->migrator->add('edutainment_themes.title_line2', null);
        $this->migrator->add('edutainment_themes.title_italic', 'Themes');
        $this->migrator->add('edutainment_themes.title_break', true);
        $this->migrator->add('edutainment_themes.intro', '<p>Every group has different learning goals. Maverick can build the programme around one theme or combine several themes into a complete itinerary.</p>');
        $this->migrator->add('edutainment_themes.cards', [
            ['icon_key' => 'dollar', 'title' => 'Business and Entrepreneurship', 'description' => 'Explore companies, startups, business districts, entrepreneurship centres and real-world examples of business growth.'],
            ['icon_key' => 'cpu', 'title' => 'Artificial Intelligence and Technology', 'description' => 'Discover how technology, automation, robotics, data and AI are influencing organisations and society.'],
            ['icon_key' => 'globe-check', 'title' => 'Sustainability and the Environment', 'description' => 'Learn about renewable energy, environmental protection, sustainable cities, conservation and responsible development.'],
            ['icon_key' => 'building', 'title' => 'Culture and Heritage', 'description' => 'Understand the history, values, traditions, architecture and lifestyles of the destination.'],
            ['icon_key' => 'star', 'title' => 'Leadership and Personal Development', 'description' => 'Strengthen confidence, collaboration, decision-making, communication and problem-solving through interactive experiences.'],
            ['icon_key' => 'wrench', 'title' => 'Science, Engineering and Innovation', 'description' => 'Visit museums, science centres, research environments and technology-focused institutions.'],
            ['icon_key' => 'grid', 'title' => 'Hospitality and Tourism', 'description' => 'Explore hotels, attractions, tourism operations, event management and destination development.'],
            ['icon_key' => 'globe', 'title' => 'Finance and International Business', 'description' => 'Learn about financial centres, global commerce, investment, banking and international markets.'],
            ['icon_key' => 'message', 'title' => 'Language and Cultural Immersion', 'description' => 'Develop greater cultural understanding through basic language exposure, traditional activities and local interaction.'],
            ['icon_key' => 'graduation-cap', 'title' => 'University and Career Exploration', 'description' => 'Help students discover higher-education environments, academic options, professional sectors and potential career pathways.'],
        ]);

        $this->migrator->add('edutainment_experiences.label', 'Experiences');
        $this->migrator->add('edutainment_experiences.title', 'What Students');
        $this->migrator->add('edutainment_experiences.title_line2', null);
        $this->migrator->add('edutainment_experiences.title_italic', 'Can Experience');
        $this->migrator->add('edutainment_experiences.title_break', true);
        $this->migrator->add('edutainment_experiences.intro', '<p>Depending on the destination and selected package, an Edutainment journey can include:</p>');
        $this->migrator->add('edutainment_experiences.categories', [
            [
                'icon_key' => 'graduation-cap',
                'title' => 'Academic Experiences',
                'items' => [
                    'University campus tours',
                    'Guest lectures and workshops',
                    'Faculty or student interactions',
                    'Educational institution visits',
                    'Research and innovation exposure',
                    'Career-orientation activities',
                ],
            ],
            [
                'icon_key' => 'briefcase',
                'title' => 'Professional Experiences',
                'items' => [
                    'Company visits',
                    'Industry presentations',
                    'Entrepreneur interactions',
                    'Startup and innovation-centre visits',
                    'Business case discussions',
                    'Workplace observation',
                ],
            ],
            [
                'icon_key' => 'building',
                'title' => 'Cultural Experiences',
                'items' => [
                    'Historical landmarks',
                    'Museums and heritage locations',
                    'Traditional arts and performances',
                    'Local cuisine experiences',
                    'Language and cultural workshops',
                    'Community interaction',
                ],
            ],
            [
                'icon_key' => 'smile',
                'title' => 'Recreational Experiences',
                'items' => [
                    'Theme parks and attractions',
                    'Adventure activities',
                    'City tours',
                    'Team-building programmes',
                    'Entertainment experiences',
                    'Group social activities',
                ],
            ],
        ]);
        $this->migrator->add('edutainment_experiences.note', 'Each programme is balanced to ensure that students have opportunities to learn, explore and enjoy the destination.');

        $this->migrator->add('edutainment_why_choose.label', 'Our Value');
        $this->migrator->add('edutainment_why_choose.title', 'Why Choose Maverick');
        $this->migrator->add('edutainment_why_choose.title_line2', null);
        $this->migrator->add('edutainment_why_choose.title_italic', 'Edutainment?');
        $this->migrator->add('edutainment_why_choose.title_break', true);
        $this->migrator->add('edutainment_why_choose.cards', [
            ['icon_key' => 'graduation-cap', 'title' => 'Education-Led Programme Design', 'description' => 'We begin with the learning purpose of the journey rather than treating the programme as an ordinary holiday.'],
            ['icon_key' => 'calendar', 'title' => 'Customised Itineraries', 'description' => 'Programmes can be customised according to destination, group age, number of participants, educational theme and budget.'],
            ['icon_key' => 'globe', 'title' => 'Local and International Destinations', 'description' => 'Choose from educational experiences within the UAE or explore international destinations through short-term study tours.'],
            ['icon_key' => 'balance', 'title' => 'Academic and Cultural Balance', 'description' => 'Our programmes are designed to balance structured educational experiences with cultural exploration and enjoyable group activities.'],
            ['icon_key' => 'users', 'title' => 'Suitable for Different Age Groups', 'description' => 'Activities can be selected according to the age, maturity and educational background of the participants.'],
            ['icon_key' => 'shield', 'title' => 'Support from Planning to Completion', 'description' => 'Our team coordinates with institutions throughout the programme-planning process.'],
            ['icon_key' => 'user-line', 'title' => 'Group Learning and Interaction', 'description' => 'Students travel, participate and reflect together, creating opportunities for teamwork and stronger peer connections.'],
            ['icon_key' => 'globe', 'title' => 'Meaningful Global Exposure', 'description' => 'International study tours introduce learners to different cultures, institutions, industries and perspectives.'],
        ]);

        $this->migrator->add('edutainment_packages.label', 'Package Details');
        $this->migrator->add('edutainment_packages.title', 'What Can Be Included in an');
        $this->migrator->add('edutainment_packages.title_line2', null);
        $this->migrator->add('edutainment_packages.title_italic', 'Edutainment Package?');
        $this->migrator->add('edutainment_packages.title_break', true);
        $this->migrator->add('edutainment_packages.intro', '<p>Package inclusions depend on the destination, group requirements and selected itinerary. A customised package may include:</p>');
        $this->migrator->add('edutainment_packages.items', [
            'Educational itinerary planning',
            'Academic and institutional visits',
            'Business or industry visits',
            'Workshops and learning activities',
            'Cultural experiences',
            'Guided sightseeing',
            'Local transportation',
            'Airport transfers',
            'Accommodation',
            'Selected meals',
            'Attraction entry tickets',
            'Tour coordinators or local guides',
            'Pre-departure orientation',
            'Travel information and programme schedules',
            'Group activities',
            'Participation certificates',
            'Photography or programme documentation',
            'Post-tour reflection activities',
        ]);
        $this->migrator->add('edutainment_packages.note', 'All confirmed inclusions, exclusions, schedules and responsibilities will be clearly stated in the final proposal.');
        $this->migrator->add('edutainment_packages.ctas', [
            ['label' => 'Request Package Details', 'url' => '/contact', 'style' => 'primary'],
        ]);

        $this->migrator->add('edutainment_institutions.label', 'For Institutions');
        $this->migrator->add('edutainment_institutions.title', 'Educational Tours for Schools');
        $this->migrator->add('edutainment_institutions.title_line2', null);
        $this->migrator->add('edutainment_institutions.title_italic', 'and Institutions');
        $this->migrator->add('edutainment_institutions.title_break', true);
        $this->migrator->add('edutainment_institutions.intro', '<p>Maverick works with educational organisations to create group experiences aligned with their requirements. Programmes can be designed for:</p>');
        $this->migrator->add('edutainment_institutions.tiles', [
            ['icon_key' => 'building', 'label' => 'School educational trips'],
            ['icon_key' => 'graduation-cap', 'label' => 'University study tours'],
            ['icon_key' => 'globe-line', 'label' => 'Student cultural exchanges'],
            ['icon_key' => 'globe', 'label' => 'International exposure visits'],
            ['icon_key' => 'monitor', 'label' => 'Business-school delegations'],
            ['icon_key' => 'book', 'label' => 'MBA study tours'],
            ['icon_key' => 'graduation-cap-user', 'label' => 'Graduation trips'],
            ['icon_key' => 'clock', 'label' => 'Summer learning programmes'],
            ['icon_key' => 'users', 'label' => 'Faculty-led educational visits'],
            ['icon_key' => 'briefcase', 'label' => 'Career-exploration trips'],
            ['icon_key' => 'star', 'label' => 'Leadership camps'],
            ['icon_key' => 'user', 'label' => 'Corporate and executive learning groups'],
        ]);
        $this->migrator->add('edutainment_institutions.note', 'Institutions can request a standard destination programme or ask for a fully customised itinerary.');
        $this->migrator->add('edutainment_institutions.ctas', [
            ['label' => 'Partner with Maverick', 'url' => '/contact', 'style' => 'primary'],
        ]);

        $this->migrator->add('edutainment_faq.label', 'FAQs');
        $this->migrator->add('edutainment_faq.title', 'Frequently Asked');
        $this->migrator->add('edutainment_faq.title_line2', null);
        $this->migrator->add('edutainment_faq.title_italic', 'Questions');
        $this->migrator->add('edutainment_faq.title_break', false);
        $this->migrator->add('edutainment_faq.items', [
            ['question' => 'What does Edutainment mean?', 'answer' => '<p>Edutainment combines education with entertainment. Maverick Edutainment uses travel, cultural experiences, academic visits, industry exposure and enjoyable activities to make learning more interactive.</p>'],
            ['question' => 'Is Edutainment the same as a normal tour?', 'answer' => '<p>No. A normal tour is primarily focused on sightseeing or recreation. An Edutainment programme is planned around specific learning objectives while still giving students opportunities to explore and enjoy the destination.</p>'],
            ['question' => 'Who can participate?', 'answer' => '<p>Programmes can be designed for school students, college students, university learners, MBA students, doctoral students, professionals and educational institutions.</p>'],
            ['question' => 'Do you organise both UAE and international tours?', 'answer' => '<p>Yes. Programmes may be planned within the UAE or for international destinations such as China, subject to availability and final confirmation.</p>'],
            ['question' => 'Can schools request a customised itinerary?', 'answer' => '<p>Yes. The itinerary can be adapted according to the students\' age, learning theme, destination, group size, programme duration and budget.</p>'],
            ['question' => 'What is included in the programme?', 'answer' => '<p>Inclusions depend on the selected package. They may include accommodation, transportation, meals, guided visits, educational activities, entry tickets, workshops, cultural experiences and programme coordination.</p>'],
            ['question' => 'Are flights included?', 'answer' => '<p>Flights may be included or excluded depending on the package requested. This will be clearly mentioned in the final proposal.</p>'],
            ['question' => 'Do students receive a certificate?', 'answer' => '<p>Participation certificates may be included for selected programmes. Certificate availability will be confirmed before the tour.</p>'],
            ['question' => 'How long are the tours?', 'answer' => '<p>Programmes may range from a one-day UAE educational visit to a multi-day or international study tour.</p>'],
            ['question' => 'Can the tour be connected to a school subject?', 'answer' => '<p>Yes. Programmes can be developed around subjects such as business, science, sustainability, history, culture, technology, engineering, tourism or leadership.</p>'],
            ['question' => 'Can parents or teachers accompany the students?', 'answer' => '<p>Accompanying arrangements can be discussed based on the institution\'s requirements, student age, destination and group structure.</p>'],
            ['question' => 'How early should we contact Maverick?', 'answer' => '<p>Institutions should contact Maverick as early as possible, particularly for international programmes that may require institutional approvals, documentation and travel arrangements.</p>'],
            ['question' => 'Is visa assistance available?', 'answer' => '<p>Visa-related guidance or coordination may be available depending on the destination and selected package. Visa approval remains subject to the relevant authority.</p>'],
            ['question' => 'How is the programme price calculated?', 'answer' => '<p>The cost depends on the destination, number of travellers, programme duration, accommodation, transport, activities, meals, flights and other requested services.</p>'],
            ['question' => 'How can we request a proposal?', 'answer' => '<p>Submit your institution name, group size, student age, preferred destination, travel period and learning objectives. Our team can then prepare an initial programme proposal.</p>'],
        ]);

        $this->migrator->add('edutainment_final_cta.heading', 'Transform a Student Trip into a');
        $this->migrator->add('edutainment_final_cta.heading_italic', 'Learning Journey');
        $this->migrator->add('edutainment_final_cta.body', '<p>Let your students discover new cultures, industries, institutions and ideas through an experience they will remember.</p><p>Whether you are planning a school educational tour within the UAE or an international student study trip to China, Maverick Edutainment can help turn your objective into a structured learning experience.</p>');
        $this->migrator->add('edutainment_final_cta.emphasis', 'See more. Experience more. Learn more.');
        $this->migrator->add('edutainment_final_cta.background_image', 'assets/images/edutainment/cta-cinematic.jpg');
        $this->migrator->add('edutainment_final_cta.background_image_asset_id', null);
        $this->migrator->add('edutainment_final_cta.ctas', [
            ['label' => 'Plan Your Educational Tour', 'url' => '/contact', 'style' => 'primary'],
            ['label' => 'Request an Itinerary', 'url' => '/contact', 'style' => 'secondary'],
        ]);
        $this->migrator->add('edutainment_final_cta.whatsapp_label', 'Enquire on WhatsApp');
        $this->migrator->add('edutainment_final_cta.show_whatsapp', true);

        $this->migrator->add('edutainment_seo.meta_title', 'Educational Tours for Students | Maverick Edutainment UAE');
        $this->migrator->add('edutainment_seo.meta_description', 'Explore UAE and international educational tours for schools, universities and student groups. Custom study trips combining learning, culture and entertainment.');
        $this->migrator->add('edutainment_seo.meta_keywords', null);
        $this->migrator->add('edutainment_seo.canonical_url', null);
        $this->migrator->add('edutainment_seo.robots', 'index, follow');
        $this->migrator->add('edutainment_seo.og_title', null);
        $this->migrator->add('edutainment_seo.og_description', null);
        $this->migrator->add('edutainment_seo.og_image_url', null);
        $this->migrator->add('edutainment_seo.og_image_url_asset_id', null);
        $this->migrator->add('edutainment_seo.og_type', 'website');
        $this->migrator->add('edutainment_seo.twitter_card', 'summary_large_image');
        $this->migrator->add('edutainment_seo.twitter_title', null);
        $this->migrator->add('edutainment_seo.twitter_description', null);
        $this->migrator->add('edutainment_seo.twitter_image_url', null);
        $this->migrator->add('edutainment_seo.twitter_image_url_asset_id', null);
        $this->migrator->add('edutainment_seo.schema_json', null);
        $this->migrator->add('edutainment_seo.custom_head_scripts', null);
        $this->migrator->add('edutainment_seo.custom_body_scripts', null);
    }
};
