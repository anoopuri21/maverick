<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('site.uk_address', '2nd Floor, College House, 17 King Edwards Road, Ruislip, London, HA4 7AE, United Kingdom');
        $this->migrator->add('site.uk_phone', '+44 7949 832387');
        $this->migrator->add('site.uk_email', 'askus@mbalondon.org.uk');
    }
};
