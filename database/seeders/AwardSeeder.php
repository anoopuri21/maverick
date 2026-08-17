<?php

namespace Database\Seeders;

use App\Models\PartnerLogo;
use Illuminate\Database\Seeder;

class AwardSeeder extends Seeder
{
    /**
     * Real awards extracted from awards.pdf (image + title + description).
     * Each maps to the certificate image embedded on its PDF page.
     */
    public function run(): void
    {
        $awards = [
            [
                'name'        => 'Top 100 Leaders in Education',
                'description' => 'Recognised at the Global Forum for Education & Learning, Le Meridian Dubai (16 Dec 2019), for significant impact and unconventional strategies in the education domain.',
                'image'       => 'awards/award_p01_01.jpg',
                'sort'        => 1,
            ],
            [
                'name'        => 'Edupreneur of the Year',
                'description' => 'Recognising outstanding entrepreneurial leadership and innovation in the education sector, and a strong commitment to impactful, progressive learning opportunities.',
                'image'       => 'awards/award_p04_02.jpg',
                'sort'        => 2,
            ],
            [
                'name'        => 'Most Entrepreneurial Institution of the Year',
                'description' => 'An acknowledgement of Maverick\'s entrepreneurial approach to education and institutional growth, reflecting its focus on innovation, accessibility and forward-thinking learning solutions.',
                'image'       => 'awards/award_p05_04.jpg',
                'sort'        => 3,
            ],
            [
                'name'        => 'Excellence in Transnational Education',
                'description' => 'Recognising Maverick Business Academy London for its contribution to delivering education across international boundaries, and its commitment to accessible, globally oriented learning.',
                'image'       => 'awards/award_p06_07.jpg',
                'sort'        => 4,
            ],
            [
                'name'        => 'Best Transnational Education Academy in UAE',
                'description' => 'An award recognising Maverick\'s contribution to transnational education within the UAE, connecting learners with international educational opportunities.',
                'image'       => 'awards/award_p07_11.jpg',
                'sort'        => 5,
            ],
            [
                'name'        => 'International Swiss Business Achievers Award',
                'description' => 'An international recognition celebrating achievement and contribution within the business and education landscape, reflecting Maverick\'s growing international presence.',
                'image'       => 'awards/award_p08_12.jpg',
                'sort'        => 6,
            ],
            [
                'name'        => 'International Outstanding B-School (Distance Learning Education) Award 2021',
                'description' => 'Recognising Maverick\'s contribution to delivering flexible, accessible distance-learning education through modern modes of learning.',
                'image'       => 'awards/award_p09_13.jpg',
                'sort'        => 7,
            ],
            [
                'name'        => 'Best Young CEO',
                'description' => 'Recognition of Mr. Fazil Sheikh\'s leadership, vision and contribution to the education sector, celebrating his role in driving the growth of Maverick Business Academy.',
                'image'       => 'awards/award_p10_14.jpg',
                'sort'        => 8,
            ],
            [
                'name'        => 'Distinguished Educator Award',
                'description' => 'An acknowledgement of distinguished contribution and commitment to the field of education, reflecting a continued focus on quality learning and development.',
                'image'       => 'awards/award_p11_15.jpg',
                'sort'        => 9,
            ],
            [
                'name'        => 'Innovative in Business Education Excellence Award — Eurasia',
                'description' => 'Recognising Maverick Business Academy London for innovation and excellence in business education, highlighting its modern, practical and progressive learning approaches.',
                'image'       => 'awards/award_p12_16.jpg',
                'sort'        => 10,
            ],
        ];

        foreach ($awards as $a) {
            PartnerLogo::updateOrCreate(
                ['name' => $a['name'], 'type' => 'award'],
                [
                    'logo_url'    => 'assets/images/' . $a['image'],
                    'description' => $a['description'],
                    'sort_order'  => $a['sort'],
                    'is_active'   => true,
                ]
            );
        }
    }
}
