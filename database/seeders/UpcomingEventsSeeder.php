<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UpcomingEventsSeeder extends Seeder
{
    /**
     * Seed the upcoming and past events from Upcoming-Events.docx.
     */
    public function run(): void
    {
        $events = [
            [
                'title' => 'Generative AI 2.0: Beyond ChatGPT, Beyond Prompts',
                'slug' => 'generative-ai-2-0-beyond-chatgpt-beyond-prompts',
                'event_date' => '2026-10-31',
                'event_start_time' => '10:00 AM',
                'event_end_time' => '2:00 PM',
                'event_type' => 'Executive Masterclass',
                'location' => 'Grand Mercure Hotel, Dubai',
                'description' => '<p>Maverick Business Academy London invites you to an exclusive Executive Masterclass facilitated by Mr. Atheef Abdul Rahman, CEO & Founder, Alutions FZE.</p><p><strong>Time:</strong> 10:00 AM – 2:00 PM (GST)</p><p>Complete the form below to confirm your participation.</p><p><strong>Registration Link:</strong> <a href="https://forms.gle/eiiLBUfAeppCr3FD6" target="_blank">https://forms.gle/eiiLBUfAeppCr3FD6</a></p>',
                'display_image' => 'events/generative-ai-2-0-beyond-chatgpt-beyond-prompts/photo_1.jpeg',
                'gallery_images' => [
                    ['url' => 'events/generative-ai-2-0-beyond-chatgpt-beyond-prompts/photo_1.jpeg']
                ],
                'is_active' => true,
            ],
            [
                'title' => '11th Annual Graduation Ceremony – 2026',
                'slug' => '11th-annual-graduation-ceremony-2026',
                'event_date' => '2026-11-29',
                'event_start_time' => '4:00 PM',
                'event_end_time' => null,
                'event_type' => 'Graduation Ceremony',
                'location' => 'Millennium Hotel, Garhoud',
                'description' => '<p>Join us as you walk across the stage, receive your well-earned recognition, celebrate with fellow graduates, faculty, family, and friends, and create memories that will last a lifetime.</p><p>📍 <strong>Venue:</strong> Millennium Hotel, Garhoud<br>📅 <strong>Date:</strong> Sunday, 29th November 2026<br>🕓 <strong>Time:</strong> 4:00 PM onwards</p>',
                'display_image' => null,
                'gallery_images' => [],
                'is_active' => true,
            ],
            [
                'title' => 'Leadership Beyond Success',
                'slug' => 'leadership-beyond-success',
                'event_date' => '2026-08-22',
                'event_start_time' => '10:00 AM',
                'event_end_time' => '2:00 PM',
                'event_type' => 'Executive Masterclass',
                'location' => 'Grand Mercure Hotel Dubai City, 61B Street, Al Garhoud, Dubai',
                'description' => '<h3>The Inner Architecture of Leaders Who Shape the Future</h3><p>Maverick Business Academy London invites you to an exclusive Executive Masterclass facilitated by Irene Riad, Founder & CEO of SightCET.</p><p>Join us for an inspiring session designed to strengthen your leadership identity, mindset, confidence, and ability to create meaningful impact.</p><p>Leadership is not defined by a title, a position, or a milestone — it is reflected in the way we think, influence, inspire, and create an impact on the people around us. Our Leadership Beyond Success Masterclass was all about exploring leadership from a deeper perspective — understanding the mindset behind effective leaders, challenging conventional thinking, and reflecting on what it truly takes to lead with purpose in today’s changing world.</p><p>The session brought together professionals, learners, and members of the Maverick community for meaningful conversations, fresh perspectives, and valuable learning beyond the classroom.</p><p>At Maverick Business Academy London, we believe education should go beyond qualifications. Experiences like these create opportunities to learn from industry professionals, exchange ideas, build stronger networks, and develop perspectives that can make a real difference in both personal and professional journeys.</p><p>A sincere thank you to everyone who joined us and contributed to the energy and engagement of the session. Learning continues. Leadership evolves. Impact grows.</p>',
                'display_image' => 'events/leadership-beyond-success/photo_1.png',
                'gallery_images' => [
                    ['url' => 'events/leadership-beyond-success/photo_1.png']
                ],
                'gallery_videos' => [
                    ['url' => 'https://youtu.be/vinCzTsL0DY?si=ETvucqVC6mu6gwVk', 'title' => 'Leadership Beyond Success - Highlight Video']
                ],
                'is_active' => true,
            ],
            [
                'title' => 'Driving Profit, Performance & Business Transformation',
                'slug' => 'driving-profit-performance-business-transformation',
                'event_date' => '2026-09-26',
                'event_start_time' => '10:00 AM',
                'event_end_time' => '12:00 PM',
                'event_type' => 'Webinar',
                'location' => 'Online / Webinar',
                'description' => '<p><strong>Event Details:</strong> September 26th 2026, 10:00 AM – 12:00 PM (GST)<br><strong>Event:</strong> Online/Webinar<br><strong>Contact us:</strong> +971547947118 | <strong>Email:</strong> <a href="mailto:studentsupport@mbalondon.org.uk">studentsupport@mbalondon.org.uk</a></p><p>70+ attendees. 20+ questions. 5 live polls. One highly engaging session.</p><p>We successfully concluded our webinar, “Driving Profit, Performance & Business Transformation,” with strong participation and insightful discussions around ERP, finance, working capital, process gaps, margin leakage, operational efficiency, and business decision-making.</p><p>The interactive case study encouraged participants to look beyond surface-level problems and ask a more important question: Are we solving the right problem?</p><p>A key takeaway from the session was simple but powerful: Diagnose before you prescribe. Understand what is happening, where it is originating, and what impact it is creating before deciding on the solution.</p><p>A sincere thank you to Sandhya Nambiar and Satish Bangera, CMA from Real Secure for such a practical and engaging session, and to every participant who contributed through questions, polls, and discussions.</p><p>More insightful sessions coming soon from Maverick Business Academy London.</p>',
                'display_image' => 'events/driving-profit-performance-business-transformation/photo_1.png',
                'gallery_images' => [
                    ['url' => 'events/driving-profit-performance-business-transformation/photo_1.png'],
                    ['url' => 'events/driving-profit-performance-business-transformation/photo_2.png'],
                    ['url' => 'events/driving-profit-performance-business-transformation/photo_3.png'],
                    ['url' => 'events/driving-profit-performance-business-transformation/photo_4.png'],
                ],
                'is_active' => true,
            ],
            [
                'title' => 'AI in Your Life, Work & Business',
                'slug' => 'ai-in-your-life-work-business',
                'event_date' => '2026-07-25',
                'event_start_time' => null,
                'event_end_time' => null,
                'event_type' => 'Webinar',
                'location' => 'Online / Webinar',
                'description' => '<p><strong>Event Timing:</strong> July 25th 2026<br><strong>Event:</strong> Online/Webinar<br><strong>Contact us:</strong> +971547947118 | <strong>Email:</strong> <a href="mailto:studentsupport@mbalondon.org.uk">studentsupport@mbalondon.org.uk</a></p><p>AI isn’t just about the tools—it’s about clarity in how you think and communicate.</p><p>We just wrapped up our game-changing webinar, “AI Basics for Everyone: How to Think, Communicate and Work with AI”, led by Mr. Atheef Abdul Rahman, Founder & CEO of Ailutions, and the response was incredible!</p><p>Here is a quick snapshot of what made the session so powerful:</p><ul><li>👥 <strong>120+ Engaged Attendees</strong> discovered how AI can dramatically enhance their daily work, future careers, and overall lifestyle.</li><li>❓ <strong>250+ Questions Answered</strong> covering everything from advanced prompt structures to practical AI integration.</li><li>💡 <strong>Myth-Busting & Mindset Shift:</strong> We broke down major AI misconceptions and proved that AI isn’t just about memorizing "perfect prompts"—it’s about deeply understanding what you want and communicating clearly.</li><li>🌐 <strong>A Growing Movement:</strong> We officially launched the AI Maverick Community—a space for forward-thinking professionals and lifelong learners!</li></ul>',
                'display_image' => 'events/ai-in-your-life-work-business/photo_1.png',
                'gallery_images' => [
                    ['url' => 'events/ai-in-your-life-work-business/photo_1.png'],
                    ['url' => 'events/ai-in-your-life-work-business/photo_2.png'],
                    ['url' => 'events/ai-in-your-life-work-business/photo_3.png'],
                    ['url' => 'events/ai-in-your-life-work-business/photo_4.png'],
                    ['url' => 'events/ai-in-your-life-work-business/photo_5.png'],
                ],
                'is_active' => true,
            ],
            [
                'title' => 'Teachers Training Workshop',
                'slug' => 'teachers-training-workshop',
                'event_date' => '2026-06-27',
                'event_start_time' => null,
                'event_end_time' => null,
                'event_type' => 'Workshop',
                'location' => 'Maverick Business Academy London',
                'description' => '<p>Our Teachers Training Workshop was a day filled with practical insights, engaging discussions, and innovative strategies designed to transform teaching for the 21st-century classroom. From effective lesson planning and smart classroom management to student engagement and the 4C’s Framework, participants walked away with valuable tools to create meaningful learning experiences.</p><p>A heartfelt thank you to Prof. Mohsin Khan for facilitating such an insightful and impactful workshop. Your expertise, passion, and dedication to empowering educators made this session truly inspiring.</p><p>Thank you to all the passionate educators who joined us and contributed to making this workshop a success. Together, we’re shaping the future of education—one classroom at a time.</p>',
                'display_image' => 'events/teachers-training-workshop/photo_1.png',
                'gallery_images' => [
                    ['url' => 'events/teachers-training-workshop/photo_1.png'],
                    ['url' => 'events/teachers-training-workshop/photo_2.png'],
                    ['url' => 'events/teachers-training-workshop/photo_3.png'],
                ],
                'is_active' => true,
            ],
            [
                'title' => 'Leading Projects in a Disruptive World',
                'slug' => 'leading-projects-in-a-disruptive-world',
                'event_date' => '2026-06-06',
                'event_start_time' => '10:00 AM',
                'event_end_time' => '2:00 PM',
                'event_type' => 'Masterclass',
                'location' => 'Novotel Hotel Al Barsha, Dubai',
                'description' => '<p>Continuous learning is at the heart of the Maverick experience. Our students will soon have the opportunity to participate in an exclusive Masterclass led by Dr. Ali Abou Abbas, Founder of Lean360, Canada.</p><p>This insightful session will explore key concepts in Agile Project Management, Lean Methodologies, Strategic Execution, Change Management, and Leadership in Dynamic Business Environments.</p><p>Students will gain practical knowledge and industry perspectives to help them navigate complex projects and lead with confidence in an ever-evolving world.</p><p>At Maverick Business Academy London, we are committed to providing our students with access to global experts, industry-relevant knowledge, and transformative learning experiences beyond the classroom.</p>',
                'display_image' => 'events/leading-projects-in-a-disruptive-world/photo_1.png',
                'gallery_images' => [
                    ['url' => 'events/leading-projects-in-a-disruptive-world/photo_1.png'],
                    ['url' => 'events/leading-projects-in-a-disruptive-world/photo_2.png'],
                    ['url' => 'events/leading-projects-in-a-disruptive-world/photo_3.png'],
                    ['url' => 'events/leading-projects-in-a-disruptive-world/photo_4.png'],
                    ['url' => 'events/leading-projects-in-a-disruptive-world/photo_5.png'],
                    ['url' => 'events/leading-projects-in-a-disruptive-world/photo_6.png'],
                ],
                'is_active' => true,
            ],
            [
                'title' => 'HR Coaching for Performance & Employee Development',
                'slug' => 'hr-coaching-for-performance-employee-development',
                'event_date' => '2026-04-11',
                'event_start_time' => '10:00 AM',
                'event_end_time' => '12:00 PM',
                'event_type' => 'Workshop',
                'location' => 'Maverick Business Academy, Al Khan, Sharjah',
                'description' => '<p><strong>Led by:</strong> Prof. Saranya Thankachan, Associate Faculty – Academics & Training<br><strong>Entry:</strong> Complimentary | <strong>Certification:</strong> UK Certificate Included</p><p>Join us at Maverick Business Academy London for an insightful and practical session designed to elevate your HR and leadership skills.</p><p>Whether you\'re an HR professional, team leader, or aspiring manager, this session will help you:</p><ul><li>✔️ Master coaching fundamentals</li><li>✔️ Handle underperformance with confidence</li><li>✔️ Lead impactful coaching conversations</li><li>✔️ Build a strong feedback culture</li><li>✔️ Practice real-world scenarios</li></ul><p>Limited seats available – don’t miss this opportunity to upgrade your professional toolkit!</p>',
                'display_image' => 'events/hr-coaching-for-performance-employee-development/photo_1.png',
                'gallery_images' => [
                    ['url' => 'events/hr-coaching-for-performance-employee-development/photo_1.png'],
                ],
                'is_active' => true,
            ],
            [
                'title' => 'Psychology of Money',
                'slug' => 'psychology-of-money',
                'event_date' => '2026-01-24',
                'event_start_time' => null,
                'event_end_time' => null,
                'event_type' => 'Masterclass',
                'location' => 'Maverick Business Academy London',
                'description' => '<p>Exclusively designed for Maverick students and alumni.</p><p>Money decisions are rarely logical—they are deeply psychological. Our recent masterclass focused on understanding money behavior, building financial discipline, and developing a long-term wealth perspective for professionals and leaders.</p><p>At Maverick Business Academy, we empower learners with insights that create both personal and professional impact.</p><p><em>Think smarter. Decide better. Grow stronger.</em></p>',
                'display_image' => 'events/psychology-of-money/photo_1.png',
                'gallery_images' => [
                    ['url' => 'events/psychology-of-money/photo_1.png'],
                    ['url' => 'events/psychology-of-money/photo_2.png'],
                    ['url' => 'events/psychology-of-money/photo_3.png'],
                    ['url' => 'events/psychology-of-money/photo_4.png'],
                    ['url' => 'events/psychology-of-money/photo_5.png'],
                ],
                'is_active' => true,
            ],
        ];

        foreach ($events as $data) {
            Event::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
