<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\SavesSettingsGroups;
use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Support\RepeaterNormalizer;
use App\Settings\DualMbaEmployersSettings;
use App\Settings\DualMbaFaqSettings;
use App\Settings\DualMbaFinalCtaSettings;
use App\Settings\DualMbaHeroSettings;
use App\Settings\DualMbaOverviewSettings;
use App\Settings\DualMbaProcessSettings;
use App\Settings\DualMbaSeoSettings;
use App\Settings\DualMbaSpecsSettings;
use App\Settings\DualMbaTestimonialsSettings;
use App\Settings\DualMbaTwiceSettings;
use App\Settings\DualMbaWhySettings;
use App\Support\DualMbaIcons;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Validation\ValidationException;
use Throwable;

class ManageDualMba extends Page implements HasForms
{
    use SavesSettingsGroups;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Global Pathways';
    protected static ?string $navigationLabel = 'Dual MBA Page';
    protected static ?int $navigationSort = 4;
    protected static string $view = 'filament.pages.manage-dual-mba';

    public array $data = [];

    public function mount(): void
    {
        $hero = app(DualMbaHeroSettings::class)->toArray();
        $hero['stats'] = array_values($hero['stats'] ?? []);
        $hero['ctas'] = array_values($hero['ctas'] ?? []);
        $hero['credentials'] = array_values($hero['credentials'] ?? []);

        $overview = app(DualMbaOverviewSettings::class)->toArray();
        $overview['cards'] = array_values($overview['cards'] ?? []);

        $twice = app(DualMbaTwiceSettings::class)->toArray();
        $twice['slides'] = $this->hydrateRepeaterMediaFields(
            array_values($twice['slides'] ?? []),
            'image'
        );

        $why = app(DualMbaWhySettings::class)->toArray();
        $why['cards'] = array_values($why['cards'] ?? []);

        $specs = app(DualMbaSpecsSettings::class)->toArray();
        $specs['cards'] = array_values($specs['cards'] ?? []);

        $employers = app(DualMbaEmployersSettings::class)->toArray();
        $employers['collage'] = $this->hydrateRepeaterMediaFields(
            array_values($employers['collage'] ?? []),
            'image'
        );
        $employers['items'] = $this->wrapStringList($employers['items'] ?? []);

        $testimonials = app(DualMbaTestimonialsSettings::class)->toArray();
        $testimonials['items'] = $this->hydrateRepeaterMediaFields(
            array_values($testimonials['items'] ?? []),
            'avatar'
        );

        $process = app(DualMbaProcessSettings::class)->toArray();
        $process['steps'] = array_values($process['steps'] ?? []);

        $faq = app(DualMbaFaqSettings::class)->toArray();
        $faq['items'] = array_values($faq['items'] ?? []);

        $finalCta = app(DualMbaFinalCtaSettings::class)->toArray();
        $finalCta['ctas'] = array_values($finalCta['ctas'] ?? []);

        $this->form->fill([
            'hero' => $hero,
            'overview' => $overview,
            'twice' => $twice,
            'why' => $why,
            'specs' => $specs,
            'employers' => $employers,
            'testimonials' => $testimonials,
            'process' => $process,
            'faq' => $faq,
            'finalCta' => $finalCta,
            'seo' => app(DualMbaSeoSettings::class)->toArray(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Tabs::make('Dual MBA')
                    ->tabs([
                        Tab::make('Hero')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                TextInput::make('hero.tag')->label('Eyebrow Tag'),
                                TextInput::make('hero.headline_line1')->label('Headline Line 1'),
                                TextInput::make('hero.headline_line2')->label('Headline Line 2'),
                                TextInput::make('hero.headline_italic')->label('Headline (Italic)'),
                                RichEditor::make('hero.sub')->label('Subheading')->columnSpanFull(),
                                Toggle::make('hero.credentials_enabled')
                                    ->label('Show UK / Swiss Credentials')
                                    ->inline(false),
                                TextInput::make('hero.credentials_label')
                                    ->label('Credentials Label (optional)')
                                    ->placeholder('Your Dual Qualification'),
                                Repeater::make('hero.credentials')
                                    ->label('Dual Credentials')
                                    ->schema([
                                        Select::make('iso2')
                                            ->label('Country')
                                            ->options([
                                                'gb' => 'United Kingdom',
                                                'ch' => 'Switzerland',
                                            ])
                                            ->required(),
                                        TextInput::make('title')->label('Credential Title')->required(),
                                        TextInput::make('subtitle')->label('Subtitle (optional)'),
                                    ])
                                    ->addable(false)
                                    ->deletable(false)
                                    ->reorderable(false)
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                                    ->columnSpanFull(),
                                TextInput::make('hero.background_image')->hidden(),
                                MediaPicker::forField('hero.background_image', 'dual-mba/hero')
                    ->label('Background Image')
                    ->columnSpanFull(),
                                TextInput::make('hero.background_image_alt')->label('Background Image Alt Text'),
                                TextInput::make('hero.visual_image')->hidden(),
                                MediaPicker::forField('hero.visual_image', 'dual-mba/hero')
                    ->label('Visual Image')
                    ->columnSpanFull(),
                                TextInput::make('hero.visual_image_alt')->label('Visual Image Alt Text'),
                                Grid::make(2)->schema([
                                    TextInput::make('hero.badge_title')->label('Badge Title'),
                                    TextInput::make('hero.badge_sub')->label('Badge Subtitle'),
                                ]),
                                Repeater::make('hero.stats')
                                    ->label('Stats')
                                    ->schema([
                                        TextInput::make('value'),
                                        TextInput::make('label'),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? $state['value'] ?? null)
                    ->columnSpanFull(),
                                $this->ctaRepeater('hero.ctas'),
                            ]),

                        Tab::make('Overview')
                            ->icon('heroicon-o-squares-2x2')
                            ->schema([
                                TextInput::make('overview.label')->label('Section Label'),
                                TextInput::make('overview.heading')->label('Heading'),
                                TextInput::make('overview.heading_highlight')->label('Heading Highlight'),
                                RichEditor::make('overview.description')->label('Description')->columnSpanFull(),
                                TextInput::make('overview.highlights_heading')->label('Highlights Heading'),
                                TextInput::make('overview.highlights_line')->label('Highlights Line')->columnSpanFull(),
                                Repeater::make('overview.cards')
                                    ->label('Highlight Cards')
                                    ->schema([
                                        $this->iconSelect(),
                                        Select::make('icon_tone')
                                            ->label('Icon Tone')
                                            ->options(['blue' => 'Blue', 'red' => 'Red'])
                                            ->default('blue'),
                                        TextInput::make('title'),
                                        RichEditor::make('text')->columnSpanFull(),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Twice')
                            ->icon('heroicon-o-film')
                            ->schema([
                                Repeater::make('twice.slides')
                                    ->label('Slides')
                                    ->schema([
                                        TextInput::make('image')->hidden(),
                                        MediaPicker::forField('image', 'dual-mba/twice')
                    ->label('Background Image')
                    ->columnSpanFull(),
                                        TextInput::make('label')->label('Eyebrow Label'),
                                        TextInput::make('title'),
                                        TextInput::make('title_italic')->label('Title (Italic)'),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Why Choose')
                            ->icon('heroicon-o-star')
                            ->schema([
                                TextInput::make('why.label')->label('Section Label'),
                                TextInput::make('why.title')->label('Title'),
                                TextInput::make('why.title_highlight')->label('Title Highlight'),
                                Repeater::make('why.cards')
                                    ->label('Why Cards')
                                    ->schema([
                                        $this->iconSelect(),
                                        TextInput::make('title'),
                                        RichEditor::make('description')->columnSpanFull(),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Specialisations')
                            ->icon('heroicon-o-cpu-chip')
                            ->schema([
                                TextInput::make('specs.label')->label('Section Label'),
                                TextInput::make('specs.title')->label('Title'),
                                TextInput::make('specs.title_highlight')->label('Title Highlight'),
                                Toggle::make('specs.title_break')->label('Line break before highlight')->inline(false),
                                RichEditor::make('specs.intro')->label('Intro')->columnSpanFull(),
                                Repeater::make('specs.cards')
                                    ->label('Specialisation Cards')
                                    ->schema([
                                        $this->iconSelect(),
                                        TextInput::make('title'),
                                        TextInput::make('tag')->label('Tag'),
                                        TextInput::make('url')->label('Link URL (optional)'),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Employers')
                            ->icon('heroicon-o-briefcase')
                            ->schema([
                                Repeater::make('employers.collage')
                                    ->label('Collage Images')
                                    ->schema([
                                        TextInput::make('image')->hidden(),
                                        MediaPicker::forField('image', 'dual-mba/employers')
                    ->label('Image')
                    ->columnSpanFull(),
                                        TextInput::make('alt')->label('Alt Text'),
                                        Select::make('role')
                                            ->options([
                                                'lead' => 'Lead',
                                                'team' => 'Team',
                                                'growth' => 'Growth',
                                            ]),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['role'] ?? $state['alt'] ?? 'Image')
                    ->columnSpanFull(),
                                TextInput::make('employers.counter_value')
                                    ->label('Counter Value (optional)')
                                    ->helperText('Leave empty to use competency list count. Use a number for animation (e.g. 8) or text (e.g. 8+).'),
                                TextInput::make('employers.counter_label')->label('Counter Label (HTML allowed for line break)')->columnSpanFull(),
                                TextInput::make('employers.label')->label('Section Label'),
                                TextInput::make('employers.heading')->label('Heading'),
                                TextInput::make('employers.heading_italic')->label('Heading (Italic)'),
                                RichEditor::make('employers.description')->label('Description')->columnSpanFull(),
                                $this->stringListRepeater('employers.items', 'Competency List'),
                            ]),

                        Tab::make('Testimonials')
                            ->icon('heroicon-o-chat-bubble-left-right')
                            ->schema([
                                TextInput::make('testimonials.label')->label('Section Label'),
                                TextInput::make('testimonials.title')->label('Title'),
                                TextInput::make('testimonials.title_italic')->label('Title (Italic)'),
                                Repeater::make('testimonials.items')
                                    ->label('Testimonials')
                                    ->schema([
                                        RichEditor::make('quote')->columnSpanFull(),
                                        Grid::make(2)->schema([
                                            TextInput::make('name'),
                                            TextInput::make('role')->label('Role / Location'),
                                        ]),
                                        TextInput::make('programme')->label('Programme')->columnSpanFull(),
                                        TextInput::make('avatar')->hidden(),
                                        MediaPicker::forField('avatar', 'dual-mba/testimonials')
                    ->label('Avatar')
                    ->columnSpanFull(),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Process')
                            ->icon('heroicon-o-numbered-list')
                            ->schema([
                                TextInput::make('process.label')->label('Section Label'),
                                TextInput::make('process.title')->label('Title'),
                                Repeater::make('process.steps')
                                    ->label('Steps')
                                    ->schema([
                                        TextInput::make('title'),
                                        RichEditor::make('description')->columnSpanFull(),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('FAQ')
                            ->icon('heroicon-o-question-mark-circle')
                            ->schema([
                                TextInput::make('faq.label')->label('Section Label'),
                                TextInput::make('faq.title')->label('Title'),
                                Repeater::make('faq.items')
                                    ->label('Questions')
                                    ->schema([
                                        TextInput::make('question')->columnSpanFull(),
                                        RichEditor::make('answer')->columnSpanFull(),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['question'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Final CTA')
                            ->icon('heroicon-o-megaphone')
                            ->schema([
                                TextInput::make('finalCta.heading')->label('Heading Line 1'),
                                TextInput::make('finalCta.heading_line2')->label('Heading Line 2'),
                                RichEditor::make('finalCta.sub')->label('Subheading')->columnSpanFull(),
                                TextInput::make('finalCta.background_image')->hidden(),
                                MediaPicker::forField('finalCta.background_image', 'dual-mba/cta')
                    ->label('Background Image')
                    ->columnSpanFull(),
                                $this->ctaRepeater('finalCta.ctas'),
                                Grid::make(2)->schema([
                                    TextInput::make('finalCta.brochure_label')->label('Brochure Link Label'),
                                    TextInput::make('finalCta.brochure_url')
                                        ->label('Brochure Link URL')
                                        ->helperText('Leave empty or use # to hide the brochure link on the page.'),
                                ]),
                            ]),

                        Tab::make('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                TextInput::make('seo.meta_title')->label('Meta Title')->maxLength(60),
                                Textarea::make('seo.meta_description')->label('Meta Description')->rows(3)->maxLength(160),
                                Textarea::make('seo.meta_keywords')->label('Meta Keywords')->rows(2),
                                Grid::make(2)->schema([
                                    TextInput::make('seo.canonical_url')->label('Canonical URL'),
                                    Select::make('seo.robots')->label('Robots')
                                        ->options([
                                            'index, follow' => 'Index, Follow (Default)',
                                            'noindex, follow' => 'No Index, Follow',
                                            'index, nofollow' => 'Index, No Follow',
                                            'noindex, nofollow' => 'No Index, No Follow',
                                        ]),
                                ]),
                                TextInput::make('seo.og_title')->label('OG Title')->maxLength(60),
                                Textarea::make('seo.og_description')->label('OG Description')->rows(3)->maxLength(200),
                                TextInput::make('seo.og_image_url')->hidden(),
                                MediaPicker::forField('seo.og_image_url', 'dual-mba/seo')->label('OG Image'),
                                Grid::make(2)->schema([
                                    Select::make('seo.og_type')->label('OG Type')
                                        ->options(['website' => 'Website', 'article' => 'Article', 'profile' => 'Profile']),
                                    Select::make('seo.twitter_card')->label('Twitter Card')
                                        ->options(['summary' => 'Summary', 'summary_large_image' => 'Summary Large Image']),
                                ]),
                                TextInput::make('seo.twitter_title')->label('Twitter Title')->maxLength(70),
                                Textarea::make('seo.twitter_description')->label('Twitter Description')->rows(3)->maxLength(200),
                                TextInput::make('seo.twitter_image_url')->hidden(),
                                MediaPicker::forField('seo.twitter_image_url', 'dual-mba/seo')->label('Twitter Image'),
                                Textarea::make('seo.schema_json')->label('Schema.org JSON-LD')->rows(6),
                                Textarea::make('seo.custom_head_scripts')->label('Custom Head Scripts')->rows(4),
                                Textarea::make('seo.custom_body_scripts')->label('Custom Body Scripts')->rows(4),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            $existingTwice = app(DualMbaTwiceSettings::class)->toArray();
            $existingEmployers = app(DualMbaEmployersSettings::class)->toArray();
            $existingTestimonials = app(DualMbaTestimonialsSettings::class)->toArray();

            $hero = $this->syncImageIfSelected($data['hero'] ?? [], 'background_image');
            $hero = $this->syncImageIfSelected($hero, 'visual_image');
            $hero['credentials'] = RepeaterNormalizer::stripEmptyRows($hero['credentials'] ?? [], ['title']);
            $hero['stats'] = RepeaterNormalizer::stripEmptyRows($hero['stats'] ?? []);
            $hero['ctas'] = RepeaterNormalizer::stripEmptyRows($hero['ctas'] ?? [], ['label', 'url']);

            $overview = $data['overview'] ?? [];
            $overview['cards'] = RepeaterNormalizer::stripEmptyRows($overview['cards'] ?? []);

            $twice = $data['twice'] ?? [];
            $twice['slides'] = $this->hydrateRepeaterMediaFields($twice['slides'] ?? [], 'image');
            foreach ($twice['slides'] ?? [] as &$slide) {
                $slide = $this->syncImageIfSelected($slide, 'image');
            }
            unset($slide);
            $twice['slides'] = $this->preserveRepeaterImageFields(
                RepeaterNormalizer::stripEmptyRows($twice['slides'] ?? []),
                $existingTwice['slides'] ?? [],
                'image'
            );

            $why = $data['why'] ?? [];
            $why['cards'] = RepeaterNormalizer::stripEmptyRows($why['cards'] ?? []);

            $specs = $data['specs'] ?? [];
            $specs['cards'] = RepeaterNormalizer::stripEmptyRows($specs['cards'] ?? []);

            $employers = $data['employers'] ?? [];
            $employers['collage'] = $this->hydrateRepeaterMediaFields($employers['collage'] ?? [], 'image');
            foreach ($employers['collage'] ?? [] as &$item) {
                $item = $this->syncImageIfSelected($item, 'image');
            }
            unset($item);
            $employers['collage'] = $this->preserveRepeaterImageFields(
                RepeaterNormalizer::stripEmptyRows($employers['collage'] ?? []),
                $existingEmployers['collage'] ?? [],
                'image'
            );
            $employers['items'] = $this->normalizeStringList($employers['items'] ?? []);

            $testimonials = $data['testimonials'] ?? [];
            $testimonials['items'] = $this->hydrateRepeaterMediaFields($testimonials['items'] ?? [], 'avatar');
            foreach ($testimonials['items'] ?? [] as &$item) {
                $item = $this->syncImageIfSelected($item, 'avatar');
            }
            unset($item);
            $testimonials['items'] = $this->preserveRepeaterImageFields(
                RepeaterNormalizer::stripEmptyRows($testimonials['items'] ?? []),
                $existingTestimonials['items'] ?? [],
                'avatar'
            );

            $process = $data['process'] ?? [];
            $process['steps'] = RepeaterNormalizer::stripEmptyRows($process['steps'] ?? []);

            $faq = $data['faq'] ?? [];
            $faq['items'] = RepeaterNormalizer::stripEmptyRows($faq['items'] ?? [], ['question']);

            $finalCta = $this->syncImageIfSelected($data['finalCta'] ?? [], 'background_image');
            $finalCta['ctas'] = RepeaterNormalizer::stripEmptyRows($finalCta['ctas'] ?? [], ['label', 'url']);

            $seo = $data['seo'] ?? [];
            $seo = $this->syncImageIfSelected($seo, 'og_image_url');
            $seo = $this->syncImageIfSelected($seo, 'twitter_image_url');

            $results = [
                $this->saveSettingsGroup(DualMbaHeroSettings::class, $hero),
                $this->saveSettingsGroup(DualMbaOverviewSettings::class, $overview),
                $this->saveSettingsGroup(DualMbaTwiceSettings::class, $twice),
                $this->saveSettingsGroup(DualMbaWhySettings::class, $why),
                $this->saveSettingsGroup(DualMbaSpecsSettings::class, $specs),
                $this->saveSettingsGroup(DualMbaEmployersSettings::class, $employers),
                $this->saveSettingsGroup(DualMbaTestimonialsSettings::class, $testimonials),
                $this->saveSettingsGroup(DualMbaProcessSettings::class, $process),
                $this->saveSettingsGroup(DualMbaFaqSettings::class, $faq),
                $this->saveSettingsGroup(DualMbaFinalCtaSettings::class, $finalCta),
                $this->saveSettingsGroup(DualMbaSeoSettings::class, $seo),
            ];

            if (in_array(false, $results, true)) {
                return;
            }

            Notification::make()
                ->title('Dual MBA page saved')
                ->success()
                ->send();
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            report($e);

            Notification::make()
                ->title('Could not save Dual MBA page')
                ->body('Please check required repeater fields and try again.')
                ->danger()
                ->send();
        }
    }

    protected function ctaRepeater(string $name): Repeater
    {
        return Repeater::make($name)
            ->label('Buttons')
            ->schema([
                TextInput::make('label'),
                TextInput::make('url'),
                Select::make('style')
                    ->options([
                        'primary' => 'Primary',
                        'secondary' => 'Secondary',
                        'outline' => 'Outline',
                    ])
                    ->default('primary'),
            ])
            ->reorderable()
            ->collapsible()
            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
            ->columnSpanFull();
    }

    protected function stringListRepeater(string $name, string $label): Repeater
    {
        return Repeater::make($name)
            ->label($label)
            ->simple(TextInput::make('item'))
            ->reorderable()
            ->columnSpanFull();
    }

    protected function iconSelect(): Select
    {
        return Select::make('icon_key')
            ->label('Icon')
            ->options(DualMbaIcons::options())
            ->searchable()
            ->nullable();
    }

    protected function syncImageIfSelected(array $payload, string $field): array
    {
        if (! empty($payload["{$field}_asset_id"])) {
            return MediaPicker::syncFieldFromAsset($payload, $field);
        }

        if (array_key_exists("{$field}_asset_id", $payload) && empty($payload["{$field}_asset_id"])) {
            return MediaPicker::syncFieldFromAsset($payload, $field);
        }

        return $payload;
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     * @return array<int, array<string, mixed>>
     */
    protected function hydrateRepeaterMediaFields(array $rows, string $field): array
    {
        foreach ($rows as &$row) {
            if (! is_array($row)) {
                continue;
            }

            $assetKey = "{$field}_asset_id";

            if (filled($row[$field] ?? null) || blank($row[$assetKey] ?? null)) {
                continue;
            }

            $row = MediaPicker::syncFieldFromAsset($row, $field);
        }

        unset($row);

        return array_values($rows);
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     * @param  array<int, array<string, mixed>>  $existingRows
     * @return array<int, array<string, mixed>>
     */
    protected function preserveRepeaterImageFields(array $rows, array $existingRows, string $field): array
    {
        $assetKey = "{$field}_asset_id";

        foreach ($rows as $index => &$row) {
            $existing = $existingRows[$index] ?? [];

            if (array_key_exists($assetKey, $row)) {
                if (empty($row[$assetKey]) && empty($row[$field])) {
                    continue;
                }
            }

            if (empty($row[$field]) && empty($row[$assetKey] ?? null)) {
                $row[$field] = $existing[$field] ?? null;
                $row[$assetKey] = $existing[$assetKey] ?? null;
            }
        }

        unset($row);

        return $rows;
    }

    protected function wrapStringList(array $items): array
    {
        return array_values(array_map(
            fn ($item) => is_string($item) ? ['item' => $item] : $item,
            $items
        ));
    }

    protected function normalizeStringList(array $items): array
    {
        return array_values(array_filter(array_map(
            fn ($item) => is_array($item) ? ($item['item'] ?? null) : $item,
            $items
        )));
    }
}
