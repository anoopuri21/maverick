<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Services\VoicesTestimonialsService;
use App\Settings\VoicesTestimonialsSettings;
use App\Support\TestimonialPages;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class VoicesTestimonials extends Component
{
    public string $page;

    public string $id;

    public ?string $label;

    public ?string $heading;

    public ?string $intro;

    public ?string $stackCaption;

    public bool $show;

    /**
     * @var Collection<int, array<string, mixed>>
     */
    public Collection $items;

    /**
     * 100% Admin-Controlled Voices Testimonials Component.
     *
     * Every piece of copy, visibility state, portrait photo, quote, and page assignment
     * comes directly from the database (`our_story_testimonials` + `settings` tables)
     * with zero hardcoded fallback strings or images.
     *
     * @param  iterable<int, array<string, mixed>>|null  $items
     */
    public function __construct(
        string $page,
        string $id = 'mlp-testimonials',
        ?string $label = null,
        ?string $heading = null,
        ?string $intro = null,
        ?string $stackCaption = null,
        bool $show = true,
        ?iterable $items = null,
    ) {
        $globalSettings = safe_settings(VoicesTestimonialsSettings::class);

        $this->page = TestimonialPages::sanitizeKey($page);
        $cleanId = preg_replace('/[^a-zA-Z0-9_-]/', '', trim($id)) ?: 'mlp-testimonials';
        $this->id = $cleanId;

        $this->label = self::resolveCopy($label, $globalSettings->label);
        $this->heading = self::resolveCopy($heading, $globalSettings->heading);
        $this->intro = self::resolveCopy($intro, $globalSettings->intro);
        $this->stackCaption = self::resolveCopy($stackCaption, $globalSettings->stack_caption);

        $this->show = (bool) ($globalSettings->show_section ?? true) && $show;

        $this->items = $items !== null
            ? collect($items)->values()
            : app(VoicesTestimonialsService::class)->itemsForPage($this->page);
    }

    public function shouldRender(): bool
    {
        return $this->show && $this->items->isNotEmpty();
    }

    public function render(): View|Closure|string
    {
        return view('components.voices-testimonials');
    }

    private static function resolveCopy(?string $override, ?string $globalDefault): ?string
    {
        if ($override !== null) {
            return trim($override);
        }

        return $globalDefault !== null ? trim($globalDefault) : null;
    }
}
