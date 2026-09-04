<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MbaMastersMastersSettings extends Settings
{
    public ?string $index = null;

    public ?string $label = null;

    public ?string $heading = null;

    public ?string $intro = null;

    public ?string $stage_image = null;

    public ?string $stage_image_asset_id = null;

    public array $universities = [];

    /** Admin-editable bar chart title. '|' splits the two-tone rendering:
     *  part before '|' renders dark navy, part after renders gold. */
    public ?string $trending_title = 'Trending|Specialisations';

    public array $trending = [
        ['label' => 'BA (Hons) Management', 'percent' => 55],
        ['label' => 'MBA (Regular & Top-up)', 'percent' => 82],
        ['label' => 'MBA in Healthcare Management', 'percent' => 64],
        ['label' => 'MBA in Quality Management', 'percent' => 48],
        ['label' => 'MBA in Finance', 'percent' => 70],
        ['label' => 'MBA in Project & Operations Management', 'percent' => 52],
        ['label' => 'MBA in Strategic HRM & Leadership', 'percent' => 45],
        ['label' => 'Executive MBA', 'percent' => 60],
    ];

    public static function group(): string
    {
        return 'mba_masters_masters';
    }
}
