<?php

namespace App\View\Components;

use App\Services\VoicesTestimonialsService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class VoicesTestimonials extends Component
{
    public string $page;

    public string $id;

    public string $label;

    public string $heading;

    public string $intro;

    public string $stackCaption;

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
     * @param  string  $page  Page registry key (e.g. 'mba-masters-landing', 'our-story', or any future key in config/testimonials.php)
     * @param  string  $id  HTML section id attribute (defaults to 'mlp-testimonials')
     * @param  ?string  $label  Optional section eyebrow label override
     * @param  ?string  $heading  Optional section H2 heading override
     * @param  ?string  $intro  Optional section intro paragraph override
     * @param  ?string  $stackCaption  Optional left portrait stack caption override
     * @param  bool  $show  Whether the section is enabled on the calling page
     * @param  iterable<int, array<string, mixed>>|null  $items  Optional pre-fetched items; when null, automatically resolves page-scoped testimonials via VoicesTestimonialsService
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
        $defaults = (array) config('testimonials.defaults', []);

        $this->page = trim($page);
        $this->id = trim($id) !== '' ? trim($id) : 'mlp-testimonials';
        $this->label = filled($label) ? trim((string) $label) : (string) ($defaults['label'] ?? 'Testimonials');
        $this->heading = filled($heading) ? trim((string) $heading) : (string) ($defaults['heading'] ?? 'What students say in their own words');
        $this->intro = $intro !== null
            ? trim($intro)
            : (string) ($defaults['intro'] ?? '');
        $this->stackCaption = filled($stackCaption)
            ? trim((string) $stackCaption)
            : (string) ($defaults['stack_caption'] ?? 'The experience, in their words.');
        $this->show = $show;

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
