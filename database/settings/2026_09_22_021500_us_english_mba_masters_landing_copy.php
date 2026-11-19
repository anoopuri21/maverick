<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $groups = [
            \App\Settings\MbaMastersHeroSettings::class,
            \App\Settings\MbaMastersTrustSettings::class,
            \App\Settings\MbaMastersOverviewSettings::class,
            \App\Settings\MbaMastersWhySettings::class,
            \App\Settings\MbaMastersJourneySettings::class,
            \App\Settings\MbaMastersMbaSettings::class,
            \App\Settings\MbaMastersMastersSettings::class,
            \App\Settings\MbaMastersClassSettings::class,
            \App\Settings\MbaMastersFeesSettings::class,
            \App\Settings\MbaMastersCareerSettings::class,
            \App\Settings\MbaMastersAlumniSettings::class,
            \App\Settings\MbaMastersPartnersSettings::class,
            \App\Settings\MbaMastersLearningSettings::class,
            \App\Settings\MbaMastersVideoTestimonialsSettings::class,
            \App\Settings\MbaMastersTestimonialsSettings::class,
            \App\Settings\MbaMastersCompareSettings::class,
            \App\Settings\MbaMastersFaqSettings::class,
            \App\Settings\MbaMastersFinalSettings::class,
            \App\Settings\MbaMastersSeoSettings::class,
        ];

        foreach ($groups as $class) {
            if (! class_exists($class)) {
                continue;
            }

            $settings = app($class);
            $group = $settings::group();
            $reflection = new ReflectionClass($class);

            foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
                if ($property->getDeclaringClass()->getName() !== $class) {
                    continue;
                }

                $name = $property->getName();
                $path = $group.'.'.$name;
                if (! $this->migrator->exists($path)) {
                    continue;
                }

                $this->migrator->update($path, fn ($value) => $this->convertField($name, $value));
            }
        }

        if (function_exists('app') && app()->bound(\Illuminate\Contracts\Console\Kernel::class)) {
            try {
                \Illuminate\Support\Facades\Artisan::call('settings:clear-cache');
            } catch (\Throwable) {
            }
        }
    }

    private function convertField(string $key, mixed $value): mixed
    {
        if ($this->skipsField($key)) {
            return $value;
        }

        if ($key === 'audience') {
            return '';
        }

        if ($key === 'metrics' && is_array($value)) {
            $metrics = json_decode(json_encode($value), true) ?: [];
            $metrics = array_values(array_filter(
                $metrics,
                fn ($metric) => ! str_contains(mb_strtolower((string) ($metric['label'] ?? '')), 'employed full time')
            ));

            return $this->convertValue($metrics);
        }

        return $this->convertValue($value);
    }

    private function skipsField(string $key): bool
    {
        return (bool) preg_match('/image|url|asset|logo|src|video|canonical|robots|schema|script|anchor|icon|photo|portrait|background/i', $key);
    }

    private function convertValue(mixed $value): mixed
    {
        if (is_string($value)) {
            return $this->american($value);
        }

        if (! is_array($value)) {
            return $value;
        }

        $copy = json_decode(json_encode($value), true);
        if (! is_array($copy)) {
            return $value;
        }

        foreach ($copy as $key => $item) {
            if (is_string($key) && $this->skipsField($key)) {
                continue;
            }
            $copy[$key] = $this->convertValue($item);
        }

        return $copy;
    }

    private function american(string $text): string
    {
        $text = preg_replace('/\s*\(Trustpilot\)/i', '', $text) ?? $text;
        $text = preg_replace('/\s+on Trustpilot\b/i', '', $text) ?? $text;
        $text = preg_replace('/\bTrustpilot\b/i', '', $text) ?? $text;

        $replacements = [
            '/\bprogrammes\b/i' => 'programs',
            '/\bprogramme\b/i' => 'program',
            '/\brecognising\b/i' => 'recognizing',
            '/\brecognised\b/i' => 'recognized',
            '/\benrolment\b/i' => 'enrollment',
            '/\benrolling\b/i' => 'enrolling',
            '/\benrols\b/i' => 'enrolls',
            '/\benrol\b/i' => 'enroll',
            '/\btowards\b/i' => 'toward',
            '/\bspecialisations\b/i' => 'specializations',
            '/\bspecialisation\b/i' => 'specialization',
            '/\borganising\b/i' => 'organizing',
            '/\borganised\b/i' => 'organized',
            '/\bfavourite\b/i' => 'favorite',
            '/\bfavour\b/i' => 'favor',
            '/\bwhilst\b/i' => 'while',
            '/\bcentres\b/i' => 'centers',
            '/\bcentre\b/i' => 'center',
        ];

        foreach ($replacements as $pattern => $replacement) {
            $text = preg_replace_callback(
                $pattern,
                fn (array $match) => $this->matchCase($match[0], $replacement),
                $text
            ) ?? $text;
        }

        $text = preg_replace('/[ \t]{2,}/', ' ', $text) ?? $text;
        $text = preg_replace('/\s+([,.])/u', '$1', $text) ?? $text;

        return trim($text);
    }

    private function matchCase(string $source, string $replacement): string
    {
        if (mb_strtoupper($source) === $source) {
            return mb_strtoupper($replacement);
        }

        $first = mb_substr($source, 0, 1);
        if (mb_strtoupper($first) === $first && mb_strtolower($first) !== $first) {
            return mb_strtoupper(mb_substr($replacement, 0, 1)).mb_substr($replacement, 1);
        }

        return $replacement;
    }
};
