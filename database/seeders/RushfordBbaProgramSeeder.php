<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\UniversityPartner;
use Illuminate\Database\Seeder;

class RushfordBbaProgramSeeder extends Seeder
{
    /**
     * Seed the Rushford Business School BBA programmes.
     *
     * php artisan db:seed --class=RushfordBbaProgramSeeder --force
     */
    public function run(): void
    {
        $category = ProgramCategory::firstOrCreate(
            ['slug' => 'bachelors'],
            ['name' => 'Bachelors', 'icon' => 'graduation-cap', 'description' => 'Undergraduate business and management programmes.', 'is_active' => true]
        );

        $university = UniversityPartner::firstOrCreate(
            ['slug' => 'rushford-business-school'],
            [
                'name' => 'Rushford Business School',
                'country' => 'Switzerland',
                'country_code' => 'CH',
                'description' => '<p>Rushford Business School, Switzerland, awards these Bachelor of Business Administration programmes. They are taught fully online with live lectures and are dual certified with Maverick Business Academy, London.</p>',
                'logo_url' => 'https://rushford.ch/wp-content/uploads/2022/12/RUSHFORD-LOGO-COLOR-1.png',
                'website_url' => 'https://rushford.ch',
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        foreach ($this->programs() as $index => $row) {
            $program = Program::updateOrCreate(
                ['slug' => $row['slug']],
                [
                    'program_category_id' => $category->id,
                    'university_partner_id' => $university->id,
                    'title' => $row['title'],
                    'duration' => '20 to 24 months',
                    'level' => 'BBA',
                    'short_description' => $row['hero'],
                    'description' => $this->paragraphs($row['overview']),
                    'is_featured' => false,
                    'is_active' => true,
                    'sort_order' => 10 + $index,
                    'highlights' => $this->pairs($row['highlights'], 'label', 'value'),
                    'snapshot' => $this->pairs($row['snapshot'], 'label', 'value'),
                    'benefits' => array_map(fn (array $benefit) => [
                        'title' => $benefit[0],
                        'desc' => '<p>'.$benefit[1].'</p>',
                        'icon' => $benefit[2],
                    ], $row['benefits']),
                    'learning' => array_map(fn (string $item) => ['item' => $item], $row['learning']),
                    'careers' => array_map(fn (string $title) => ['title' => $title], $row['careers']),
                    'structure' => array_map(fn (array $stage) => [
                        'title' => $stage['title'],
                        'subtitle' => $stage['subtitle'] ?? '',
                        'modules' => array_map(function (array|string $module) {
                            if (is_string($module)) {
                                return ['title' => $module];
                            }

                            $mapped = ['title' => $module['title']];
                            if (filled($module['overview'] ?? null)) {
                                $mapped['overview'] = '<p>'.$module['overview'].'</p>';
                            }

                            return $mapped;
                        }, $stage['modules']),
                    ], $row['structure']),
                    'support' => array_map(fn (string $item) => ['item' => $item], $row['support']),
                    'gcc_heading' => 'Why GCC Students Choose This Programme',
                    'gcc_reasons' => $row['gcc'],
                    'fees' => array_map(fn (string $title) => ['title' => $title], $row['fees']),
                    'testimonials' => [],
                    'reviews' => [],
                ]
            );

            $program->seo()->updateOrCreate([], [
                'meta_title' => $row['meta_title'],
                'meta_description' => $row['meta_description'],
                'canonical_url' => null,
            ]);

            $this->command?->info("Seeded programme: {$program->title}");
        }
    }

    /**
     * @param  list<string>  $paragraphs
     */
    private function paragraphs(array $paragraphs): string
    {
        return collect($paragraphs)
            ->map(fn (string $paragraph) => '<p>'.$paragraph.'</p>')
            ->implode('');
    }

    /**
     * @param  list<array{0: string, 1: string}>  $rows
     * @return list<array<string, string>>
     */
    private function pairs(array $rows, string $left, string $right): array
    {
        return array_map(fn (array $row) => [$left => $row[0], $right => $row[1]], $rows);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function programs(): array
    {
        return require __DIR__.'/data/rushford_bba.php';
    }
}
