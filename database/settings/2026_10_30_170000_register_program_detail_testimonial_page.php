<?php

use App\Support\TestimonialPages;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $group = 'voices_testimonials';
        $name = 'pages';
        $setting = $group.'.'.$name;
        $programDetailPage = [
            'key' => TestimonialPages::PROGRAM_DETAIL,
            'label' => 'Program Detail Pages (/programs/{slug})',
        ];

        if (! $this->migrator->exists($setting)) {
            $this->migrator->add($setting, [
                [
                    'key' => TestimonialPages::MBA_MASTERS_LANDING,
                    'label' => "Master's Landing Page (/online-mba-masters-uae)",
                ],
                [
                    'key' => TestimonialPages::OUR_STORY,
                    'label' => 'Our Story Page (/our-story)',
                ],
                $programDetailPage,
            ]);

            return;
        }

        $row = DB::table('settings')
            ->where('group', $group)
            ->where('name', $name)
            ->first();

        if (! $row) {
            return;
        }

        $pages = json_decode((string) $row->payload, true);
        if (! is_array($pages)) {
            return;
        }

        foreach ($pages as $page) {
            if (
                is_array($page)
                && TestimonialPages::sanitizeKey($page['key'] ?? null) === TestimonialPages::PROGRAM_DETAIL
            ) {
                return;
            }
        }

        $pages[] = $programDetailPage;

        DB::table('settings')
            ->where('group', $group)
            ->where('name', $name)
            ->update([
                'payload' => json_encode(array_values($pages), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]);
    }

    public function down(): void
    {
        // Keep this Admin-managed page option on rollback so saved assignments are not orphaned.
    }
};
