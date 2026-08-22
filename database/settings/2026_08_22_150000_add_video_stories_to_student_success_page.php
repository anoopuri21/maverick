<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        if (! $this->migrator->exists('student_success_page.video_section_label')) {
            $this->migrator->add('student_success_page.video_section_label', 'Video Stories');
        }
        if (! $this->migrator->exists('student_success_page.video_section_heading')) {
            $this->migrator->add('student_success_page.video_section_heading', 'Video Success');
        }
        if (! $this->migrator->exists('student_success_page.video_section_heading_italic')) {
            $this->migrator->add('student_success_page.video_section_heading_italic', 'Stories');
        }
        if (! $this->migrator->exists('student_success_page.video_section_subheading')) {
            $this->migrator->add('student_success_page.video_section_subheading', 'Hear from our graduates in their own words.');
        }
        if (! $this->migrator->exists('student_success_page.video_stories')) {
            $this->migrator->add('student_success_page.video_stories', []);
        }
    }
};
