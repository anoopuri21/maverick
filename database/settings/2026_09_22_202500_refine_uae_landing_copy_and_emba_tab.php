<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->update(
            'mba_masters_trust.label',
            fn () => 'Rated 4.9 out of 5 on Google and Edarabia'
        );

        $this->migrator->update(
            'mba_masters_overview.intro',
            fn () => 'This is an MBA in Dubai for working professionals, the way our students talk about it after a term or two.'
        );

        $this->migrator->update(
            'mba_masters_why.heading',
            fn () => 'Why Maverick Fits a Working Life in the UAE'
        );

        $this->migrator->update('mba_masters_mba.tabs', function ($tabs) {
            $tabs = json_decode(json_encode($tabs ?? []), true);
            if (! is_array($tabs)) {
                return [];
            }

            $mbaIndex = null;
            $embaIndex = null;
            foreach ($tabs as $index => $tab) {
                $key = (string) ($tab['key'] ?? '');
                $label = (string) ($tab['label'] ?? '');
                if ($key === 'gau-mba' || $label === 'MBA, Girne American University (North Cyprus)') {
                    $mbaIndex = $index;
                }
                if ($key === 'gau-emba' || str_starts_with($label, 'Executive MBA, Girne American University')) {
                    $embaIndex = $index;
                }
            }

            if ($mbaIndex === null || $embaIndex === null || ! isset($tabs[$mbaIndex]['universities'][0])) {
                return $tabs;
            }

            $programs = $tabs[$mbaIndex]['universities'][0]['programs'] ?? [];
            $known = [];
            foreach ($programs as $program) {
                $title = (string) ($program['title'] ?? '');
                if ($title !== '') {
                    $known[$title] = true;
                }
            }

            foreach ($tabs[$embaIndex]['universities'][0]['programs'] ?? [] as $program) {
                $title = (string) ($program['title'] ?? '');
                if ($title === '' || isset($known[$title])) {
                    continue;
                }
                $programs[] = $program;
                $known[$title] = true;
            }

            $tabs[$mbaIndex]['label'] = 'EMBA, Girne American University (North Cyprus)';
            $tabs[$mbaIndex]['universities'][0]['programs'] = array_values($programs);
            if (isset($tabs[$mbaIndex]['universities'][0]['specification']) && is_array($tabs[$mbaIndex]['universities'][0]['specification'])) {
                $tabs[$mbaIndex]['universities'][0]['specification']['programme_count'] = (string) count($programs);
            }

            unset($tabs[$embaIndex]);

            return array_values($tabs);
        });

        if (function_exists('app') && app()->bound(\Illuminate\Contracts\Console\Kernel::class)) {
            try {
                \Illuminate\Support\Facades\Artisan::call('settings:clear-cache');
            } catch (\Throwable) {
            }
        }
    }
};
