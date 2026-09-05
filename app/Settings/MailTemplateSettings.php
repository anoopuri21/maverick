<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MailTemplateSettings extends Settings
{
    /** Rich text shown above the form details in every FormMailer email. Empty = section skipped. */
    public ?string $header_html = null;

    /** Rich text shown below the form details (regards / signature). Empty = section skipped. */
    public ?string $footer_html = null;

    public static function group(): string
    {
        return 'mail_template';
    }
}
