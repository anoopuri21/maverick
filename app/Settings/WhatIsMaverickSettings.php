<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class WhatIsMaverickSettings extends Settings
{
    public ?string $heading = null;
    public ?string $statement1 = null;
    public ?string $statement2 = null;
    public ?string $statement3 = null;
    public ?string $statement4 = null;
    public ?string $statement5 = null;
    public ?string $final_text = null;

    public static function group(): string
    {
        return 'what_is_maverick';
    }
}