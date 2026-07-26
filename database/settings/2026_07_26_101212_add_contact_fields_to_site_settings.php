<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('site.phone_secondary', '+971 4 123 4567');
        $this->migrator->add('site.office_hours', 'Mon - Fri, 9:00 AM - 6:00 PM');
        $this->migrator->add('site.twitter_url', 'https://x.com/maverickuni/');
    }
};
