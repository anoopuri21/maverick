<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GlobalOpportunitiesSettings extends Settings
{
    public ?string $heading = null;
    public ?string $subtitle = null;

    public ?string $left_title = null;
    public ?string $right_title = null;

    // Scalable repeaters (source of truth going forward)
    public array $opportunities = []; // [{title, desc, slug, image, image_url, coming_soon}]
    public array $pathways = [];      // [{title, desc, slug, image, image_url, coming_soon}]

    // Legacy positional fields (kept for backward-compat; removed after migrate)
    public ?string $opp1_title = null;
    public ?string $opp1_desc = null;
    public ?string $opp1_url = null;
    public ?string $opp2_title = null;
    public ?string $opp2_desc = null;
    public ?string $opp2_url = null;
    public ?string $opp3_title = null;
    public ?string $opp3_desc = null;
    public ?string $opp3_url = null;
    public ?string $opp4_title = null;
    public ?string $opp4_desc = null;
    public ?string $opp4_url = null;

    public ?string $path1_title = null;
    public ?string $path1_desc = null;
    public ?string $path1_url = null;
    public ?string $path2_title = null;
    public ?string $path2_desc = null;
    public ?string $path2_url = null;
    public ?string $path3_title = null;
    public ?string $path3_desc = null;
    public ?string $path3_url = null;
    public ?string $path4_title = null;
    public ?string $path4_desc = null;
    public ?string $path4_url = null;

    /** Resolved opportunities list (repeater, else legacy fields). */
    public function getOpportunitiesListAttribute(): array
    {
        if (! empty($this->opportunities)) {
            return array_values($this->opportunities);
        }
        $items = [];
        for ($i = 1; $i <= 4; $i++) {
            $t = $this->{'opp'.$i.'_title'} ?? null;
            if ($t !== null && $t !== '') {
                $items[] = [
                    'title' => $t,
                    'desc'  => $this->{'opp'.$i.'_desc'} ?? null,
                    'url'   => $this->{'opp'.$i.'_url'} ?? null,
                ];
            }
        }
        return $items;
    }

    /** Resolved pathways list (repeater, else legacy fields). */
    public function getPathwaysListAttribute(): array
    {
        if (! empty($this->pathways)) {
            return array_values($this->pathways);
        }
        $items = [];
        for ($i = 1; $i <= 4; $i++) {
            $t = $this->{'path'.$i.'_title'} ?? null;
            if ($t !== null && $t !== '') {
                $items[] = [
                    'title' => $t,
                    'desc'  => $this->{'path'.$i.'_desc'} ?? null,
                    'url'   => $this->{'path'.$i.'_url'} ?? null,
                ];
            }
        }
        return $items;
    }

    public static function group(): string
    {
        return 'global_opportunities';
    }
}
