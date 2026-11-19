<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->update('mba_masters_mba.tabs', function ($tabs) {
            $tabs = json_decode(json_encode($tabs ?? []), true);
            if (! is_array($tabs)) {
                $tabs = [];
            }

            $seeded = [];
            foreach ($this->catalog() as $entry) {
                $existing = $this->matchingTab($tabs, $entry['key'], $entry['name']);
                $university = $this->matchingUniversity($existing, $entry['name']);
                $specification = is_array($university['specification'] ?? null) ? $university['specification'] : [];
                $specification['programme_count'] = $entry['programme_count'];

                $seeded[] = [
                    'key' => $entry['key'],
                    'label' => $entry['label'],
                    'universities' => [[
                        'name' => $entry['name'],
                        'logo' => $university['logo'] ?? null,
                        'image' => $university['image'] ?? null,
                        'logo_asset_id' => $university['logo_asset_id'] ?? null,
                        'image_asset_id' => $university['image_asset_id'] ?? null,
                        'specification' => $specification,
                        'programs' => array_map(
                            fn (string $title) => ['title' => $title],
                            $entry['programs']
                        ),
                    ]],
                ];
            }

            return $seeded;
        });

        if (function_exists('app') && app()->bound(\Illuminate\Contracts\Console\Kernel::class)) {
            try {
                \Illuminate\Support\Facades\Artisan::call('settings:clear-cache');
            } catch (\Throwable) {
            }
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $tabs
     * @return array<string, mixed>|null
     */
    private function matchingTab(array $tabs, string $key, string $name): ?array
    {
        foreach ($tabs as $tab) {
            if (($tab['key'] ?? '') === $key) {
                return $tab;
            }
        }

        foreach ($tabs as $tab) {
            foreach ($tab['universities'] ?? [] as $university) {
                if (($university['name'] ?? '') === $name) {
                    return $tab;
                }
            }
        }

        return null;
    }

    /**
     * @param  array<string, mixed>|null  $tab
     * @return array<string, mixed>
     */
    private function matchingUniversity(?array $tab, string $name): array
    {
        if (! is_array($tab)) {
            return [];
        }

        foreach ($tab['universities'] ?? [] as $university) {
            if (($university['name'] ?? '') === $name) {
                return $university;
            }
        }

        $first = $tab['universities'][0] ?? null;

        return is_array($first) ? $first : [];
    }

    /**
     * @return array<int, array{key: string, label: string, name: string, programme_count: string, programs: array<int, string>}>
     */
    private function catalog(): array
    {
        return [
            [
                'key' => 'rbs-mba',
                'label' => 'MBA, Rushford Business School (Switzerland)',
                'name' => 'Rushford Business School (RBS), Switzerland',
                'programme_count' => '14',
                'programs' => [
                    'MBA in Sustainability, Energy and Environment',
                    'MBA in Strategic Management',
                    'MBA in Real Estate Management',
                    'MBA in Human Resource Management',
                    'MBA in Marketing',
                    'MBA in Logistics & Supply Chain Management',
                    'MBA in Healthcare Leadership',
                    'MBA in Hospitality & Tourism Management',
                    'MBA in Health Economics',
                    'MBA in Entrepreneurship and Innovation',
                    'MBA in Finance',
                    'MBA in Artificial Intelligence',
                    'MBA in International Business',
                    'Master of Business Administration (MBA)',
                ],
            ],
            [
                'key' => 'gau-mba',
                'label' => 'EMBA, Girne American University (North Cyprus)',
                'name' => 'Girne American University (GAU), North Cyprus',
                'programme_count' => '22',
                'programs' => [
                    'MBA in Business Management',
                    'MBA in Financial Management',
                    'MBA in International Business Management',
                    'MBA in Management Information Systems',
                    'MBA in Marketing',
                    'MBA in Data Science/Analytics Management',
                    'Executive MBA in Educational Leadership',
                    'Executive MBA in Media & Entertainment',
                    'Executive MBA in Global Banking & Finance',
                    'Executive MBA in Health & Safety Leadership',
                    'Executive MBA in Renewable Energy & Sustainability',
                    'Executive MBA in Tourism & Hospitality Management',
                    'Executive MBA in Innovation & Entrepreneurship',
                    'Executive MBA in Project Management',
                    'Executive MBA in Human Resources Management',
                    'Executive MBA in Supply Chain Management',
                    'Executive MBA in Health Care Management',
                    'Executive MBA in Engineering Management',
                    'Executive MBA in Public Administration',
                    'Executive MBA in Public Health',
                    'Executive MBA in Digital Marketing',
                    'Executive MBA in Sport Management',
                ],
            ],
            [
                'key' => 'uws-mba',
                'label' => 'MBA in International Business, University of the West of Scotland (UK)',
                'name' => 'University of the West of Scotland (UWS), UK',
                'programme_count' => '1',
                'programs' => [
                    'MBA in International Business',
                ],
            ],
        ];
    }
};
