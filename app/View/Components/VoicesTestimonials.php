<?php

namespace App\View\Components;

use App\Services\VoicesTestimonialsService;
use App\Settings\VoicesTestimonialsSettings;
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
     * @var Collection<int, array{
     *     name: string,
     *     role: string,
     *     quote: string,
     *     photo: ?string,
     *     initial: string
     * }>
     */
    public Collection $items;

    /**
     * 100% Admin-Controlled Voices Testimonials Component.
     *
     * Every piece of copy, visibility state, portrait photo, quote, and page assignment
     * comes directly from the database (`our_story_testimonials` + `settings` tables)
     * with zero hardcoded fallback strings or images.
     *
     * @param  string  $page  Page registry key (e.g. 'mba-masters-landing', 'our-story')
     * @param  string  $id  HTML section id attribute
     * @param  ?string  $label  Optional page-level DB setting label; falls back to global DB `VoicesTestimonialsSettings::$label` when null
     * @param  ?string  $heading  Optional page-level DB setting heading; falls back to global DB `VoicesTestimonialsSettings::$heading` when null
     * @param  ?string  $intro  Optional page-level DB setting intro; falls back to global DB `VoicesTestimonialsSettings::$intro` when null
     * @param  ?string  $stackCaption  Optional page-level DB caption; falls back to global DB `VoicesTestimonialsSettings::$stack_caption` when null
     * @param  bool  $show  Page-level visibility toggle from DB settings
     * @param  iterable<int, array<string, mixed>>|null  $items  Optional explicit collection; defaults to DB `VoicesTestimonialsService::itemsForPage($page)`
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

        $this->page = trim($page);
        $this->id = trim($id) !== '' ? trim($id) : 'mlp-testimonials';

        // Strict DB-only resolution: explicit page DB setting if provided, otherwise global DB setting;
        // if an admin clears a field to empty string "", keep it empty instead of injecting hardcoded text.
        $this->label = $label !== null ? trim($label) : ($globalSettings->label !== null ? trim($globalSettings->label) : null);
        $this->heading = $heading !== null ? trim($heading) : ($globalSettings->heading !== null ? trim($globalSettings->heading) : null);
        $this->intro = $intro !== null ? trim($intro) : ($globalSettings->intro !== null ? trim($globalSettings->intro) : null);
        $this->stackCaption = $stackCaption !== null
            ? trim($stackCaption)
            : ($globalSettings->stack_caption !== null ? trim($globalSettings->stack_caption) : null);

        $globalEnabled = (bool) ($globalSettings->show_section ?? true);
        $this->show = $globalEnabled && $show;

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
}
