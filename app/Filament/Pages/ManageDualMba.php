<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
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
    use HandlesCloudinaryImageFields;
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

        $overview = app(DualMbaOverviewSettings::class)->toArray();
        $overview['cards'] = array_values($overview['cards'] ?? []);

        $twice = app(DualMbaTwiceSettings::class)->toArray();
        $twice['slides'] = array_values($twice['slides'] ?? []);

        $why = app(DualMbaWhySettings::class)->toArray();
        $why['cards'] = array_values($why['cards'] ?? []);

        $specs = app(DualMbaSpecsSettings::class)->toArray();
        $specs['cards'] = array_values($specs['cards'] ?? []);

        $employers = app(DualMbaEmployersSettings::class)->toArray();
        $employers['collage'] = array_values($employers['collage'] ?? []);
        $employers['items'] = $this->wrapStringList($employers['items'] ?? []);

        $testimonials = app(DualMbaTestimonialsSettings::class)->toArray();
        $testimonials['items'] = array_values($testimonials['items'] ?? []);

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
                                TextInput::make('hero.background_image')->hidden(),
                                MediaPicker::forField('hero.background_image', 'dual-mba/hero')
                                    ->label('Background Image')
                                    ->columnSpanFull(),
                                TextInput::make('hero.visual_image')->hidden(),
                                MediaPicker::forField('hero.visual_image', 'dual-mba/hero')
                                    ->label('Visual Image')
                                    ->columnSpanFull(),
                                Grid::make(2)->schema([
                                    TextInput::make('hero.badge_title')->label('Badge Title'),
                                    TextInput::make('hero.badge_sub')->label('Badge Subtitle'),
                                ]),
                                Repeater::make('hero.stats')
                                    ->label('Stats')
                                    ->schema([
                                        TextInput::make('value')->required(),
                                        TextInput::make('label')->required(),
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
                                        TextInput::make('title')->required(),
                                        Textarea::make('text')->rows(3)->columnSpanFull(),
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
                                        TextInput::make('title')->required(),
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
                                        TextInput::make('title')->required(),
                                        Textarea::make('description')->rows(3)->columnSpanFull(),
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
                                        TextInput::make('title')->required(),
                                        TextInput::make('tag')->label('Tag'),
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
                                        Textarea::make('quote')->rows(4)->columnSpanFull(),
                                        Grid::make(2)->schema([
                                            TextInput::make('name')->required(),
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
                                        TextInput::make('title')->required(),
                                        Textarea::make('description')->rows(3)->columnSpanFull(),
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
                                        TextInput::make('question')->required()->columnSpanFull(),
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
                                    TextInput::make('finalCta.brochure_url')->label('Brochure Link URL'),
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

            $hero = $this->syncImageIfSelected($data['hero'] ?? [], 'background_image');
            $hero = $this->syncImageIfSelected($hero, 'visual_image');
            $hero['stats'] = array_values($hero['stats'] ?? []);
            $hero['ctas'] = array_values($hero['ctas'] ?? []);

            $overview = $data['overview'] ?? [];
            $overview['cards'] = array_values($overview['cards'] ?? []);

            $twice = $data['twice'] ?? [];
            foreach ($twice['slides'] ?? [] as &$slide) {
                $slide = $this->syncImageIfSelected($slide, 'image');
            }
            unset($slide);
            $twice['slides'] = array_values($twice['slides'] ?? []);

            $why = $data['why'] ?? [];
            $why['cards'] = array_values($why['cards'] ?? []);

            $specs = $data['specs'] ?? [];
            $specs['cards'] = array_values($specs['cards'] ?? []);

            $employers = $data['employers'] ?? [];
            foreach ($employers['collage'] ?? [] as &$item) {
                $item = $this->syncImageIfSelected($item, 'image');
            }
            unset($item);
            $employers['collage'] = array_values($employers['collage'] ?? []);
            $employers['items'] = $this->normalizeStringList($employers['items'] ?? []);

            $testimonials = $data['testimonials'] ?? [];
            foreach ($testimonials['items'] ?? [] as &$item) {
                $item = $this->syncImageIfSelected($item, 'avatar');
            }
            unset($item);
            $testimonials['items'] = array_values($testimonials['items'] ?? []);

            $process = $data['process'] ?? [];
            $process['steps'] = array_values($process['steps'] ?? []);

            $faq = $data['faq'] ?? [];
            $faq['items'] = array_values($faq['items'] ?? []);

            $finalCta = $this->syncImageIfSelected($data['finalCta'] ?? [], 'background_image');
            $finalCta['ctas'] = array_values($finalCta['ctas'] ?? []);

            $seo = $data['seo'] ?? [];
            $seo = $this->syncImageIfSelected($seo, 'og_image_url');
            $seo = $this->syncImageIfSelected($seo, 'twitter_image_url');

            $this->saveSettingsGroup(DualMbaHeroSettings::class, $hero);
            $this->saveSettingsGroup(DualMbaOverviewSettings::class, $overview);
            $this->saveSettingsGroup(DualMbaTwiceSettings::class, $twice);
            $this->saveSettingsGroup(DualMbaWhySettings::class, $why);
            $this->saveSettingsGroup(DualMbaSpecsSettings::class, $specs);
            $this->saveSettingsGroup(DualMbaEmployersSettings::class, $employers);
            $this->saveSettingsGroup(DualMbaTestimonialsSettings::class, $testimonials);
            $this->saveSettingsGroup(DualMbaProcessSettings::class, $process);
            $this->saveSettingsGroup(DualMbaFaqSettings::class, $faq);
            $this->saveSettingsGroup(DualMbaFinalCtaSettings::class, $finalCta);
            $this->saveSettingsGroup(DualMbaSeoSettings::class, $seo);

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

    /** @param  class-string  $settingsClass */
    protected function saveSettingsGroup(string $settingsClass, array $payload): void
    {
        $settings = app($settingsClass);
        $payload = $this->ensureAllSettingsProperties($settings, $payload);
        $payload = $this->preserveExistingImageFields($payload, $settings);
        $this->ensureSettingsRowsExist($settings);
        app()->forgetInstance($settingsClass);
        app($settingsClass)->fill($payload)->save();
    }

    protected function ensureAllSettingsProperties(object $settings, array $payload): array
    {
        $reflection = new \ReflectionClass($settings);

        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $name = $property->getName();

            if (! array_key_exists($name, $payload)) {
                $payload[$name] = $settings->{$name} ?? $property->getDefaultValue();
            }
        }

        return $payload;
    }

    protected function ensureSettingsRowsExist(object $settings): void
    {
        $mapper = app(\Spatie\LaravelSettings\SettingsMapper::class);
        $getConfig = new \ReflectionMethod($mapper, 'getConfig');
        $getConfig->setAccessible(true);
        $config = $getConfig->invoke($mapper, get_class($settings));

        $repo = $config->getRepository();
        $group = $config->getGroup();
        $existing = collect($repo->getPropertiesInGroup($group))->keys();

        foreach ($config->getReflectedProperties()->keys() as $name) {
            if (! $existing->contains($name)) {
                $repo->createProperty($group, $name, $settings->{$name} ?? null);
            }
        }
    }

    protected function ctaRepeater(string $name): Repeater
    {
        return Repeater::make($name)
            ->label('Buttons')
            ->schema([
                TextInput::make('label')->required(),
                TextInput::make('url')->required(),
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
            ->simple(TextInput::make('item')->required())
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

        return $payload;
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
