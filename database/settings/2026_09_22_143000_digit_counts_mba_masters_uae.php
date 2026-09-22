<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->update('mba_masters_overview.intro', function ($intro) {
            return str_replace('Five things', '5 things', (string) $intro);
        });

        $this->migrator->update('mba_masters_overview.items', function ($items) {
            $items = $this->rows($items);
            foreach ($items as &$item) {
                $item['text'] = str_replace(
                    ['all three', 'only one'],
                    ['all 3', 'only 1'],
                    (string) ($item['text'] ?? '')
                );
            }
            unset($item);

            return $items;
        });

        $this->migrator->update('mba_masters_journey.heading', function ($heading) {
            return str_replace('Five Steps', '5 Steps', (string) $heading);
        });

        $this->migrator->update('mba_masters_class.regions', function ($regions) {
            $regions = $this->rows($regions);
            foreach ($regions as &$region) {
                $region['note'] = str_replace(
                    ['About half', 'About a quarter'],
                    ['About 50%', 'About 25%'],
                    (string) ($region['note'] ?? '')
                );
            }
            unset($region);

            return $regions;
        });

        $this->migrator->update('mba_masters_career.stories', function ($stories) {
            $stories = $this->rows($stories);
            foreach ($stories as &$story) {
                $story['quote'] = str_replace(
                    ['six months', 'a second branch', 'her first regional'],
                    ['6 months', 'a 2nd branch', 'her 1st regional'],
                    (string) ($story['quote'] ?? '')
                );
            }
            unset($story);

            return $stories;
        });

        $this->migrator->update('mba_masters_learning.points', function ($points) {
            $points = $this->rows($points);
            foreach ($points as &$point) {
                $point['text'] = str_replace(
                    'One named coach',
                    '1 named coach',
                    (string) ($point['text'] ?? '')
                );
            }
            unset($point);

            return $points;
        });

        $this->migrator->update('mba_masters_faq.items', function ($items) {
            $items = $this->rows($items);
            foreach ($items as &$item) {
                $item['answer'] = str_replace(
                    ['two to three years', 'five-minute'],
                    ['2 to 3 years', '5-minute'],
                    (string) ($item['answer'] ?? '')
                );
            }
            unset($item);

            return $items;
        });

        if (function_exists('app') && app()->bound(\Illuminate\Contracts\Console\Kernel::class)) {
            try {
                \Illuminate\Support\Facades\Artisan::call('settings:clear-cache');
            } catch (\Throwable) {
            }
        }
    }

    private function rows(mixed $value): array
    {
        $rows = json_decode(json_encode($value ?? []), true);

        return is_array($rows) ? $rows : [];
    }
};
