<?php

namespace App\Console\Commands;

use App\Models\OurStoryTimeline;
use Illuminate\Console\Command;

class SyncOurStoryTimeline extends Command
{
    protected $signature = 'our-story:sync-timeline';
    protected $description = 'Sync the Our Story timeline entries with exact SOP content';

    public function handle(): int
    {
        $this->info('Syncing Our Story Timeline milestones...');

        $entries = [
            [
                'year' => '2018',
                'title' => 'Maverick Business Academy Established',
                'description' => 'Founded with a vision to provide world-class, internationally recognized qualifications to learners.',
                'sort_order' => 10,
            ],
            [
                'year' => '2019',
                'title' => 'First International Academic Partnerships',
                'description' => 'Established key academic collaborations with leading international institutions and universities.',
                'sort_order' => 20,
            ],
            [
                'year' => '2020',
                'title' => 'Expansion of Online Learning Solutions',
                'description' => 'Launched state-of-the-art virtual learning environments to support flexible and remote education globally.',
                'sort_order' => 30,
            ],
            [
                'year' => '2022',
                'title' => 'Growth Across GCC and International Markets',
                'description' => 'Extended our presence and network across the Gulf Cooperation Council (GCC) and other global regions.',
                'sort_order' => 40,
            ],
            [
                'year' => '2024',
                'title' => 'Expansion of Doctoral and Executive Education Programs',
                'description' => 'Introduced advanced academic offerings including executive education and doctoral pathway solutions.',
                'sort_order' => 50,
            ],
            [
                'year' => '2025',
                'title' => 'International Conferences, Industry Partnerships & Global Collaborations',
                'description' => 'Hosted international symposia and built pioneering research and enterprise collaborations worldwide.',
                'sort_order' => 60,
            ],
            [
                'year' => 'Today',
                'title' => 'Empowering Learners Worldwide Through Global Education Pathways',
                'description' => 'Continuing to drive innovation, excellence, and career-transforming learning opportunities for students worldwide.',
                'sort_order' => 70,
            ],
        ];

        foreach ($entries as $entry) {
            OurStoryTimeline::updateOrCreate(
                [
                    'year' => $entry['year'],
                    'title' => $entry['title'],
                ],
                [
                    'description' => $entry['description'],
                    'sort_order' => $entry['sort_order'],
                    'is_active' => true,
                ]
            );
            $this->line("Synced milestone: {$entry['year']} - {$entry['title']}");
        }

        $this->info('Timeline sync complete!');
        return self::SUCCESS;
    }
}
