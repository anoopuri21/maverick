<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('zoho.enabled', false);
        $this->migrator->add('zoho.smtp_host', 'smtp.zoho.com');
        $this->migrator->add('zoho.port', 587);
        $this->migrator->add('zoho.encryption', 'tls');
        $this->migrator->add('zoho.username', null);
        $this->migrator->add('zoho.password', null, true);
        $this->migrator->add('zoho.from_name', 'Maverick Business Academy');
        $this->migrator->add('zoho.reply_to', null);
        $this->migrator->add('zoho.zoho_mail_domain', null);
        $this->migrator->add('zoho.default_recipient', null);
    }
};
