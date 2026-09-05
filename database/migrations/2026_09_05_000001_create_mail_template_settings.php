<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('mail_template.header_html', null);

        $this->migrator->add(
            'mail_template.footer_html',
            '<p>Regards,<br><strong>Maverick Business Academy</strong></p>'
        );
    }
};
