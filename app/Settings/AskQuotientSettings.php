<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AskQuotientSettings extends Settings
{
    public ?string $label = null;

    public ?string $heading = null;

    public ?string $description = null;

    public ?string $card_a_letter = null;

    public ?string $card_a_heading = null;

    public ?string $card_a_keywords = null;

    public ?string $card_a_definition = null;

    public ?string $card_s_letter = null;

    public ?string $card_s_heading = null;

    public ?string $card_s_keywords = null;

    public ?string $card_s_definition = null;

    public ?string $card_k_letter = null;

    public ?string $card_k_heading = null;

    public ?string $card_k_keywords = null;

    public ?string $card_k_definition = null;

    public static function group(): string
    {
        return 'ask_quotient';
    }
}
