<?php

declare(strict_types=1);

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class VoicesTestimonialsSettings extends Settings
{
    public bool $show_section = true;

    public ?string $label = null;

    public ?string $heading = null;

    public ?string $intro = null;

    public ?string $stack_caption = null;

    /**
     * Dynamic list of `{key: string, label: string}` display page targets managed from Admin.
     *
     * @var array<int, array{key?: string, label?: string}>
     */
    public array $pages = [];

    public static function group(): string
    {
        return 'voices_testimonials';
    }
}
