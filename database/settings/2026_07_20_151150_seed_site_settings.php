<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class SeedSiteSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('site.logo_url', 'assets/images/logo.png');
        $this->migrator->add('site.logo_white_url', 'assets/images/logo-white.png');
        $this->migrator->add('site.phone', '+971 67 675 656');
        $this->migrator->add('site.whatsapp_number', '971501441670');
        $this->migrator->add('site.email', 'admissions@mbalondon.org.uk');
        $this->migrator->add('site.address', '#201, 2nd Floor, Robot Park Tower, Sharjah, United Arab Emirates');
        $this->migrator->add('site.facebook_url', 'https://www.facebook.com/maverickuni/');
        $this->migrator->add('site.instagram_url', 'https://www.instagram.com/maverickuni/');
        $this->migrator->add('site.linkedin_url', 'https://www.linkedin.com/company/80568787/');
        $this->migrator->add('site.youtube_url', 'https://www.youtube.com/@MaverickBusinessAcademyLondon');
        $this->migrator->add('site.apply_now_url', '/apply/');
    }
}