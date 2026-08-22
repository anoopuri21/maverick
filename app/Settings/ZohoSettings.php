<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ZohoSettings extends Settings
{
    public bool $enabled = false;

    public ?string $smtp_host = 'smtp.zoho.com';

    public ?int $port = 587;

    public ?string $encryption = 'tls';

    public ?string $username = null;

    public ?string $password = null;

    public ?string $from_name = null;

    public ?string $reply_to = null;

    public ?string $zoho_mail_domain = null;

    public ?string $default_recipient = null;

    public static function group(): string
    {
        return 'zoho';
    }
}
