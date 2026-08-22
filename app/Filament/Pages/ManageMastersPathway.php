<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\EnsuresSettingsRowsExist;
use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Settings\MpAudienceSettings;
use App\Settings\MpDestinationsSettings;
use App\Settings\MpFinalCtaSettings;
use App\Settings\MpHeroSettings;
use App\Settings\MpHowSettings;
use App\Settings\MpNoticeSettings;
use App\Settings\MpOverviewSettings;
use App\Settings\MpProcessSettings;
use App\Settings\MpRequirementsSettings;
use App\Settings\MpSeoSettings;
use App\Settings\MpWhySettings;
use App\Support\MpIcons;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Validation\ValidationException;
use Throwable;

class ManageMastersPathway extends Page implements HasForms
{
    use HandlesCloudinaryImageFields;
    use EnsuresSettingsRowsExist;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Global Pathways';
    protected static ?string $navigationLabel = "Master's Pathway Page";
    protected static ?int $navigationSort = 7;
    protected static string $view = 'filament.pages.manage-masters-pathway';

    public array $data = [];

    public function mount(): void
    {
        $hero = app(MpHeroSettings::class)->toArray();
        $hero['paragraphs'] = $this->wrapHtmlList($hero['paragraphs'] ?? []);
        $hero['ctas'] = array_values($hero['ctas'] ?? []);
        $hero['route_steps'] = array_values($hero['route_steps'] ?? []);

        $overview = app(MpOverviewSettings::class)->toArray();
        $overview['paragraphs'] = $this->wrapHtmlList($overview['paragraphs'] ?? []);
        $overview['phases'] = array_values($overview['phases'] ?? []);

        $how = app(MpHowSettings::class)->toArray();
        $how['phases'] = array_values(array_map(function ($phase) {
            $phase['facts'] = array_values($phase['facts'] ?? []);

            return $phase;
        }, $how['phases'] ?? []));

        $destinations = app(MpDestinationsSettings::class)->toArray();
        $destinations['items'] = $this->wrapNestedLists($destinations['items'] ?? [], 'points');

        $why = app(MpWhySettings::class)->toArray();
        $why['items'] = array_values($why['items'] ?? []);

        $audience = app(MpAudienceSettings::class)->toArray();
        $audience['items'] = $this->wrapStringList($audience['items'] ?? []);

        $requirements = app(MpRequirementsSettings::class)->toArray();
        $requirements['items'] = $this->wrapStringList($requirements['items'] ?? []);

        $process = app(MpProcessSettings::class)->toArray();
        $process['steps'] = array_values($process['steps'] ?? []);

        $finalCta = app(MpFinalCtaSettings::class)->toArray();
        $finalCta['ctas'] = array_values($finalCta['ctas'] ?? []);
        $finalCta['contacts'] = array_values($finalCta['contacts'] ?? []);

        $this->form->fill([
            'hero' => $hero,
            'overview' => $overview,
            'how' => $how,
            'destinations' => $destinations,
            'why' => $why,
            'audience' => $audience,
            'requirements' => $requirements,
            'process' => $process,
            'notice' => app(MpNoticeSettings::class)->toArray(),
            'finalCta' => $finalCta,
            'seo' => app(MpSeoSettings::class)->toArray(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Tabs::make("Master's Pathway")
                    ->tabs([
                        Tab::make('Hero')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                TextInput::make('hero.tag')->label('Eyebrow Tag'),
                                TextInput::make('hero.heading')->label('Heading'),
                                TextInput::make('hero.heading_highlight')->label('Heading Highlight'),
                                RichEditor::make('hero.sub')->label('Subheading')->columnSpanFull(),
                                Repeater::make('hero.paragraphs')
                                    ->label('Paragraphs')
                                    ->schema([RichEditor::make('html')->label('Paragraph')->columnSpanFull()])
                                    ->reorderable()->collapsible()->columnSpanFull(),
                                TextInput::make('hero.background_image')->hidden(),
                                MediaPicker::forField('hero.background_image', 'mp/hero')
                    ->label('Background Image')->columnSpanFull(),
                                Repeater::make('hero.ctas')
                                    ->label('Buttons')
                                    ->schema([
                                        TextInput::make('label'),
                                        TextInput::make('url'),
                                        Select::make('style')->options([
                                            'primary' => 'Primary',
                                            'secondary' => 'Secondary',
                                            'ghost' => 'Ghost',
                                        ])->default('primary'),
                                    ])
                    ->reorderable()->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                    ->columnSpanFull(),
                                Repeater::make('hero.route_steps')
                                    ->label('Route Steps')
                                    ->schema([TextInput::make('label')])
                                    ->reorderable()->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('Overview')
                            ->icon('heroicon-o-squares-2x2')
                            ->schema([
                                TextInput::make('overview.label')->label('Section Label'),
                                TextInput::make('overview.heading')->label('Heading'),
                                TextInput::make('overview.heading_highlight')->label('Heading Highlight'),
                                Repeater::make('overview.paragraphs')
                                    ->label('Paragraphs')
                                    ->schema([RichEditor::make('html')->label('Paragraph')->columnSpanFull()])
                                    ->reorderable()->collapsible()->columnSpanFull(),
                                Repeater::make('overview.phases')
                                    ->label('Pathway Phases')
                                    ->schema([
                                        TextInput::make('label')->label('Phase Label'),
                                        TextInput::make('title')->label('Title'),
                                        TextInput::make('meta')->label('Meta'),
                                        RichEditor::make('desc')->label('Description')->columnSpanFull(),
                                    ])
                    ->reorderable()->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('How it works')
                            ->icon('heroicon-o-map')
                            ->schema([
                                TextInput::make('how.label')->label('Section Label'),
                                TextInput::make('how.heading')->label('Heading'),
                                TextInput::make('how.heading_highlight')->label('Heading Highlight'),
                                Repeater::make('how.phases')
                                    ->label('Phases')
                                    ->schema([
                                        TextInput::make('num')->label('Phase Number'),
                                        TextInput::make('title')->label('Title'),
                                        TextInput::make('sub')->label('Subtitle')->columnSpanFull(),
                                        Repeater::make('facts')
                                            ->label('Facts')
                                            ->schema([
                                                TextInput::make('label')->label('Label'),
                                                TextInput::make('value')->label('Value'),
                                            ])
                    ->reorderable()->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                    ->columnSpanFull(),
                                    ])
                    ->reorderable()->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
                                RichEditor::make('how.notice')->label('Notice')->columnSpanFull(),
                            ]),

                        Tab::make('Destinations')
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                TextInput::make('destinations.label')->label('Section Label'),
                                TextInput::make('destinations.heading')->label('Heading'),
                                TextInput::make('destinations.heading_highlight')->label('Heading Highlight'),
                                RichEditor::make('destinations.sub')->label('Subheading')->columnSpanFull(),
                                Repeater::make('destinations.items')
                                    ->label('Destinations')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('slug')->label('Slug'),
                                            Select::make('position')->options(['left' => 'Image Left', 'right' => 'Image Right'])->default('left'),
                                        ]),
                                        TextInput::make('name')->label('Country Name'),
                                        TextInput::make('label')->label('Eyebrow Label'),
                                        TextInput::make('university')->label('University')->columnSpanFull(),
                                        RichEditor::make('description')->label('Description')->columnSpanFull(),
                                        TextInput::make('image')->hidden(),
                                        MediaPicker::forField('image', 'mp/destinations')->label('Image')->columnSpanFull(),
                                        $this->stringListRepeater('points', 'Points'),
                                        Textarea::make('best_for')->label('Best Suited For')->rows(2)->columnSpanFull(),
                                        Textarea::make('qualification')->label('Qualification Note')->rows(2)->columnSpanFull(),
                                    ])
                    ->reorderable()->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Why Choose')
                            ->icon('heroicon-o-star')
                            ->schema([
                                TextInput::make('why.label')->label('Section Label'),
                                TextInput::make('why.heading')->label('Heading'),
                                TextInput::make('why.heading_highlight')->label('Heading Highlight'),
                                TextInput::make('why.statement')->label('Statement')->columnSpanFull(),
                                Repeater::make('why.items')
                                    ->label('Benefits')
                                    ->schema([
                                        TextInput::make('title')->label('Title'),
                                        RichEditor::make('desc')->label('Description')->columnSpanFull(),
                                    ])
                    ->reorderable()->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Audience')
                            ->icon('heroicon-o-user-group')
                            ->schema([
                                TextInput::make('audience.label')->label('Section Label'),
                                TextInput::make('audience.heading')->label('Heading'),
                                TextInput::make('audience.heading_highlight')->label('Heading Highlight'),
                                TextInput::make('audience.statement')->label('Statement')->columnSpanFull(),
                                $this->stringListRepeater('audience.items', 'Audience Items'),
                            ]),

                        Tab::make('Requirements')
                            ->icon('heroicon-o-clipboard-document-check')
                            ->schema([
                                TextInput::make('requirements.label')->label('Section Label'),
                                TextInput::make('requirements.heading')->label('Heading'),
                                TextInput::make('requirements.heading_highlight')->label('Heading Highlight'),
                                RichEditor::make('requirements.intro')->label('Intro')->columnSpanFull(),
                                $this->stringListRepeater('requirements.items', 'Requirements'),
                            ]),

                        Tab::make('Process')
                            ->icon('heroicon-o-list-bullet')
                            ->schema([
                                TextInput::make('process.label')->label('Section Label'),
                                TextInput::make('process.heading')->label('Heading'),
                                TextInput::make('process.heading_highlight')->label('Heading Highlight'),
                                Repeater::make('process.steps')
                                    ->label('Steps')
                                    ->schema([
                                        $this->iconSelect(),
                                        TextInput::make('num')->label('Number (e.g. 01)'),
                                        TextInput::make('title')->label('Title'),
                                        RichEditor::make('desc')->label('Description')->columnSpanFull(),
                                    ])
                    ->reorderable()->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Notice')
                            ->icon('heroicon-o-exclamation-triangle')
                            ->schema([
                                TextInput::make('notice.label')->label('Label'),
                                RichEditor::make('notice.body')->label('Body')->columnSpanFull(),
                            ]),

                        Tab::make('Final CTA')
                            ->icon('heroicon-o-megaphone')
                            ->schema([
                                TextInput::make('finalCta.eyebrow')->label('Eyebrow'),
                                TextInput::make('finalCta.heading')->label('Heading'),
                                TextInput::make('finalCta.heading_highlight')->label('Heading Highlight'),
                                RichEditor::make('finalCta.sub')->label('Subheading')->columnSpanFull(),
                                RichEditor::make('finalCta.description')->label('Description')->columnSpanFull(),
                                Repeater::make('finalCta.ctas')
                                    ->label('Buttons')
                                    ->schema([
                                        TextInput::make('label'),
                                        TextInput::make('url'),
                                        Select::make('style')->options([
                                            'solid' => 'Solid',
                                            'outline' => 'Outline',
                                        ])->default('solid'),
                                    ])
                    ->reorderable()->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                    ->columnSpanFull(),
                                Repeater::make('finalCta.contacts')
                                    ->label('Contact Lines')
                                    ->schema([
                                        TextInput::make('label'),
                                        TextInput::make('url')->label('URL (optional)'),
                                    ])
                    ->reorderable()->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                TextInput::make('seo.meta_title')->label('Meta Title')->maxLength(70),
                                Textarea::make('seo.meta_description')->label('Meta Description')->rows(3)->maxLength(160),
                                TextInput::make('seo.meta_keywords')->label('Meta Keywords'),
                                TextInput::make('seo.canonical_url')->label('Canonical URL'),
                                TextInput::make('seo.robots')->label('Robots'),
                                TextInput::make('seo.og_title')->label('OG Title'),
                                Textarea::make('seo.og_description')->label('OG Description')->rows(3),
                                TextInput::make('seo.og_image_url')->hidden(),
                                MediaPicker::forField('seo.og_image_url', 'mp/seo')->label('OG Image'),
                                Grid::make(2)->schema([
                                    Select::make('seo.og_type')->label('OG Type')
                                        ->options(['website' => 'Website', 'article' => 'Article', 'profile' => 'Profile']),
                                    Select::make('seo.twitter_card')->label('Twitter Card')
                                        ->options(['summary' => 'Summary', 'summary_large_image' => 'Summary Large Image']),
                                ]),
                                TextInput::make('seo.twitter_title')->label('Twitter Title')->maxLength(70),
                                Textarea::make('seo.twitter_description')->label('Twitter Description')->rows(3)->maxLength(200),
                                TextInput::make('seo.twitter_image_url')->hidden(),
                                MediaPicker::forField('seo.twitter_image_url', 'mp/seo')->label('Twitter Image'),
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
            $hero['paragraphs'] = $this->normalizeHtmlList($hero['paragraphs'] ?? []);
            $hero['ctas'] = array_values($hero['ctas'] ?? []);
            $hero['route_steps'] = array_values($hero['route_steps'] ?? []);

            $overview = $data['overview'] ?? [];
            $overview['paragraphs'] = $this->normalizeHtmlList($overview['paragraphs'] ?? []);
            $overview['phases'] = array_values($overview['phases'] ?? []);

            $how = $data['how'] ?? [];
            $how['phases'] = array_values(array_map(function ($phase) {
                $phase['facts'] = array_values($phase['facts'] ?? []);

                return $phase;
            }, $how['phases'] ?? []));

            $destinations = $data['destinations'] ?? [];
            foreach ($destinations['items'] ?? [] as &$item) {
                $item = $this->syncImageIfSelected($item, 'image');
            }
            unset($item);
            $destinations['items'] = $this->normalizeNestedLists($destinations['items'] ?? [], 'points');

            $why = $data['why'] ?? [];
            $why['items'] = array_values($why['items'] ?? []);

            $audience = $data['audience'] ?? [];
            $audience['items'] = $this->normalizeStringList($audience['items'] ?? []);

            $requirements = $data['requirements'] ?? [];
            $requirements['items'] = $this->normalizeStringList($requirements['items'] ?? []);

            $process = $data['process'] ?? [];
            $process['steps'] = array_values($process['steps'] ?? []);

            $notice = $data['notice'] ?? [];

            $finalCta = $data['finalCta'] ?? [];
            $finalCta['ctas'] = array_values($finalCta['ctas'] ?? []);
            $finalCta['contacts'] = array_values($finalCta['contacts'] ?? []);

            $seo = $data['seo'] ?? [];
            $seo = $this->syncImageIfSelected($seo, 'og_image_url');
            $seo = $this->syncImageIfSelected($seo, 'twitter_image_url');

            $this->saveSettingsGroup(MpHeroSettings::class, $hero);
            $this->saveSettingsGroup(MpOverviewSettings::class, $overview);
            $this->saveSettingsGroup(MpHowSettings::class, $how);
            $this->saveSettingsGroup(MpDestinationsSettings::class, $destinations);
            $this->saveSettingsGroup(MpWhySettings::class, $why);
            $this->saveSettingsGroup(MpAudienceSettings::class, $audience);
            $this->saveSettingsGroup(MpRequirementsSettings::class, $requirements);
            $this->saveSettingsGroup(MpProcessSettings::class, $process);
            $this->saveSettingsGroup(MpNoticeSettings::class, $notice);
            $this->saveSettingsGroup(MpFinalCtaSettings::class, $finalCta);
            $this->saveSettingsGroup(MpSeoSettings::class, $seo);

            Notification::make()->title("Master's Pathway page saved")->success()->send();
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            report($e);
            Notification::make()
                ->title("Could not save Master's Pathway page")
                ->body('Please check repeater fields and try again.')
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
            ->options(MpIcons::options())
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

    protected function wrapHtmlList(array $items): array
    {
        return array_values(array_map(
            fn ($item) => is_string($item) ? ['html' => $item] : $item,
            $items
        ));
    }

    protected function normalizeHtmlList(array $items): array
    {
        return array_values(array_filter(array_map(
            fn ($item) => is_array($item) ? ($item['html'] ?? null) : $item,
            $items
        )));
    }

    protected function wrapNestedLists(array $cards, string $key): array
    {
        return array_values(array_map(function ($card) use ($key) {
            $card[$key] = $this->wrapStringList($card[$key] ?? []);

            return $card;
        }, $cards));
    }

    protected function normalizeNestedLists(array $cards, string $key): array
    {
        return array_values(array_map(function ($card) use ($key) {
            $card[$key] = $this->normalizeStringList($card[$key] ?? []);

            return $card;
        }, $cards));
    }
}
