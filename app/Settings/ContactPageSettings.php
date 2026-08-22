<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ContactPageSettings extends Settings
{
    public ?string $eyebrow = null;
    public ?string $heading = null;
    public ?string $description = null;
    public ?string $label_address = null;
    public ?string $label_email = null;
    public ?string $label_phone = null;
    public ?string $label_hours = null;
    public ?string $label_social = null;
    public ?string $form_title = null;
    public ?string $form_subtitle = null;
    public ?string $success_message = null;

    public static function group(): string
    {
        return 'contact_page';
    }
}