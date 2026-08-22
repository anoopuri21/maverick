<?php

namespace Database\Seeders;

use App\Models\GupPartnerUniversity;
use App\Models\PartnershipGalleryItem;
use Illuminate\Database\Seeder;

class GlobalUniversityPartnersSeeder extends Seeder
{
    public function run(): void
    {
        $universities = [
            [
                'slug' => 'rbs',
                'name' => 'Rushford Business School',
                'abbreviation' => 'RBS',
                'country' => 'Switzerland',
                'flag_emoji' => '🇨🇭',
                'recognition' => 'International business school focused on management, leadership, and executive education.',
                'logo_url' => 'assets/images/universities/rbs-logo.png',
                'sort_order' => 1,
            ],
            [
                'slug' => 'gau',
                'name' => 'Girne American University',
                'abbreviation' => 'GAU',
                'country' => 'North Cyprus',
                'flag_emoji' => '🇨🇾',
                'recognition' => 'Internationally recognized university offering undergraduate, postgraduate, and doctoral programs.',
                'logo_url' => 'assets/images/universities/gau-logo.png',
                'sort_order' => 2,
            ],
            [
                'slug' => 'gloucestershire',
                'name' => 'University of the West of Scotland',
                'abbreviation' => 'UWS',
                'country' => 'United Kingdom',
                'flag_emoji' => '🇬🇧',
                'recognition' => 'A renowned UK university with a strong reputation for business, leadership, and professional education.',
                'logo_url' => 'assets/images/universities/uog-logo.png',
                'sort_order' => 3,
            ],
            [
                'slug' => 'uca',
                'name' => 'University of the Creative Arts',
                'abbreviation' => 'UCA',
                'country' => 'United Kingdom',
                'flag_emoji' => '🇬🇧',
                'recognition' => 'One of the UK\'s leading specialist universities for business, creativity, and innovation.',
                'logo_url' => 'assets/images/universities/uca-logo.png',
                'sort_order' => 4,
            ],
            [
                'slug' => 'wolverhampton',
                'name' => 'University of Wolverhampton',
                'abbreviation' => 'UoW',
                'country' => 'Turks & Caicos Islands',
                'flag_emoji' => '🇹🇨',
                'recognition' => 'International institution offering business, education, health sciences, and doctoral qualifications.',
                'logo_url' => 'assets/images/universities/uow-logo.png',
                'sort_order' => 5,
            ],
            [
                'slug' => 'gatehouse',
                'name' => 'Gatehouse University',
                'abbreviation' => 'GH',
                'country' => 'North Cyprus',
                'flag_emoji' => '🇨🇾',
                'recognition' => 'Internationally recognized university offering undergraduate, postgraduate, and doctoral programs.',
                'logo_url' => 'assets/images/universities/gau-logo.png',
                'sort_order' => 6,
            ],
            [
                'slug' => 'qualifi',
                'name' => 'Qualifi University',
                'abbreviation' => 'QU',
                'country' => 'North Cyprus',
                'flag_emoji' => '🇨🇾',
                'recognition' => 'Internationally recognized university offering undergraduate, postgraduate, and doctoral programs.',
                'logo_url' => 'assets/images/universities/gau-logo.png',
                'sort_order' => 7,
            ],
        ];

        foreach ($universities as $data) {
            GupPartnerUniversity::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, ['cta_url' => url('/programs'), 'is_active' => true])
            );
        }

        $gallery = [
            ['category' => 'mou-signings', 'badge' => 'Forum', 'event_date' => '2025-02-14', 'title' => null, 'caption' => 'Cross-Border Learning Roundtable', 'image_url' => 'https://images.pexels.com/photos/11299318/pexels-photo-11299318.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=940', 'size' => 'medium', 'sort_order' => 1],
            ['category' => 'mou-signings', 'badge' => 'MOU SIGNING', 'event_date' => '2024-10-28', 'title' => null, 'caption' => null, 'image_url' => 'https://images.pexels.com/photos/7433919/pexels-photo-7433919.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=940', 'size' => 'tall', 'sort_order' => 2],
            ['category' => 'university-visits', 'badge' => 'UNIVERSITY VISIT', 'event_date' => '2024-06-03', 'title' => 'Partner Campus Tour — Toronto', 'caption' => 'Scoping new articulation routes across North America.', 'image_url' => 'https://images.pexels.com/photos/27238168/pexels-photo-27238168.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=700', 'size' => 'tall', 'sort_order' => 3],
            ['category' => 'graduations', 'badge' => 'Conference', 'event_date' => '2024-12-18', 'title' => 'Global Education Summit London', 'caption' => null, 'image_url' => 'https://images.pexels.com/photos/2774556/pexels-photo-2774556.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=940', 'size' => 'tall', 'sort_order' => 4],
            ['category' => 'mou-signings', 'badge' => 'MOU SIGNING', 'event_date' => '2024-05-17', 'title' => 'Kuala Lumpur Academic Alliance', 'caption' => null, 'image_url' => 'https://images.pexels.com/photos/17682883/pexels-photo-17682883.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=800&w=800', 'size' => 'medium', 'sort_order' => 5],
            ['category' => 'graduations', 'badge' => 'GRADUATION', 'event_date' => '2024-12-18', 'title' => 'Class of 2024 — Caps in the Air', 'caption' => null, 'image_url' => 'https://images.pexels.com/photos/31290544/pexels-photo-31290544.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=940', 'size' => 'medium', 'sort_order' => 6],
            ['category' => 'conferences', 'badge' => 'CONFERENCE', 'event_date' => '2024-11-05', 'title' => 'UK–Oman Digital Connectivity Forum', 'caption' => null, 'image_url' => 'https://images.pexels.com/photos/29335353/pexels-photo-29335353.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=940', 'size' => 'medium', 'sort_order' => 7],
            ['category' => 'forums', 'badge' => 'FORUM', 'event_date' => '2024-03-21', 'title' => 'International Education Forum', 'caption' => null, 'image_url' => 'https://images.pexels.com/photos/7640781/pexels-photo-7640781.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=800&w=800', 'size' => 'wide', 'sort_order' => 8],
        ];

        foreach ($gallery as $item) {
            PartnershipGalleryItem::updateOrCreate(
                [
                    'category' => $item['category'],
                    'badge' => $item['badge'],
                    'event_date' => $item['event_date'],
                    'title' => $item['title'],
                ],
                array_merge($item, ['is_active' => true])
            );
        }
    }
}
