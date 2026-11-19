<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\UniversityPartner;
use Illuminate\Database\Seeder;

class MasterProgramsSeeder extends Seeder
{
    /**
     * Seed master's programmes from uploads/master-programs.
     *
     * php artisan db:seed --class=MasterProgramsSeeder --force
     */
    public function run(): void
    {
        $data = require __DIR__.'/data/master_programs.php';

        $categories = [];
        foreach ($data['categories'] as $category) {
            $categories[$category['slug']] = ProgramCategory::firstOrCreate(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'icon' => 'graduation-cap',
                    'description' => $category['name'].' programmes.',
                    'is_active' => true,
                    'sort_order' => $category['sort_order'],
                ]
            );
        }

        $universities = [];
        foreach ($data['universities'] as $university) {
            $universities[$university['slug']] = UniversityPartner::firstOrCreate(
                ['slug' => $university['slug']],
                [
                    'name' => $university['name'],
                    'country' => $university['country'],
                    'country_code' => $university['country_code'],
                    'is_active' => true,
                    'sort_order' => $university['sort_order'],
                ]
            );
        }

        foreach ($data['programs'] as $row) {
            $program = Program::firstOrNew(['slug' => $row['slug']]);
            $creating = ! $program->exists;

            $program->fill([
                'program_category_id' => $categories[$row['category_slug']]->id,
                'university_partner_id' => $universities[$row['university_slug']]->id,
                'title' => $row['title'],
                'duration' => $row['duration'],
                'level' => $row['level'],
                'short_description' => $row['hero'],
                'description' => $this->paragraphs($row['overview']),
                'is_active' => true,
                'sort_order' => $row['sort_order'],
                'highlights' => $this->pairs($row['highlights'], 'label', 'value'),
                'snapshot' => $this->pairs($row['snapshot'], 'label', 'value'),
                'benefits' => array_map(fn (array $benefit) => [
                    'title' => $benefit[0],
                    'desc' => $benefit[1] !== '' ? '<p>'.$benefit[1].'</p>' : '',
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
            ]);

            if ($creating) {
                $program->is_featured = false;
                $program->testimonials = [];
                $program->reviews = [];
            }

            $program->save();

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
}
