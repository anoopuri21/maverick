<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class DeiMatrixSettings extends Settings
{
    public ?string $label = null;

    public ?string $heading = null;

    public ?string $description = null;

    public ?string $row_d_letter = null;

    public ?string $row_d_heading = null;

    public ?string $row_d_definition = null;

    public ?string $row_d_practice = null;

    public ?string $row_e_letter = null;

    public ?string $row_e_heading = null;

    public ?string $row_e_definition = null;

    public ?string $row_e_practice = null;

    public ?string $row_i_letter = null;

    public ?string $row_i_heading = null;

    public ?string $row_i_definition = null;

    public ?string $row_i_practice = null;

    public static function group(): string
    {
        return 'dei_matrix';
    }
}
