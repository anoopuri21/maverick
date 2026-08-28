<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $keys = [
            'global_access_points.label' => 'Global Reach',
            'global_access_points.heading_line1' => 'Our Global',
            'global_access_points.heading_line2' => 'Maverick Access Points',
            'global_access_points.story_label' => 'A world in motion',
            'global_access_points.story_heading' => 'Learning that travels with you.',
            'global_access_points.story_p1' => 'From the Gulf to the wider world, the Access Points network keeps the learning conversation open across borders.',
            'global_access_points.story_p2' => 'Select a country to bring its point into focus, then drag the globe to explore the wider constellation.',
            'global_access_points.hint' => 'Grab the globe to explore',
            'global_access_points.canvas_aria' => 'Interactive globe showing Maverick Access Points',
        ];

        foreach ($keys as $key => $default) {
            if (! $this->migrator->exists($key)) {
                $this->migrator->add($key, $default);
            }
        }
    }
};
