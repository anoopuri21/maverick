<?php

namespace App\Support;

class ZapierEvents
{
    public const CONTACT_SUBMITTED = 'contact.submitted';

    public const NEWSLETTER_SUBSCRIBED = 'newsletter.subscribed';

    public const PROGRAM_ENQUIRY_SUBMITTED = 'program.enquiry_submitted';

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::CONTACT_SUBMITTED => 'Contact form submitted',
            self::NEWSLETTER_SUBSCRIBED => 'Newsletter signup',
            self::PROGRAM_ENQUIRY_SUBMITTED => 'Programme enquiry submitted',
        ];
    }
}
