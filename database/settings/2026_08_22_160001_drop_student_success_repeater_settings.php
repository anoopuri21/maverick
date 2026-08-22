<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        if ($this->migrator->exists('student_success_page.stories')) {
            $this->migrator->delete('student_success_page.stories');
        }
        if ($this->migrator->exists('student_success_page.video_stories')) {
            $this->migrator->delete('student_success_page.video_stories');
        }
    }
};
