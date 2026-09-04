<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HydratesRepeaterMediaFields;
use App\Filament\Concerns\SavesSettingsGroups;
use App\Filament\Forms\Components\MediaPicker;
use App\Settings\GbpAdmissionSettings;
use App\Settings\GbpAreasSettings;
use App\Settings\GbpComparisonSettings;
use App\Settings\GbpCostSettings;
use App\Settings\GbpDestinationsSettings;
use App\Settings\GbpDocumentsSettings;
use App\Settings\GbpExploreSettings;
use App\Settings\GbpFinalCtaSettings;
use App\Settings\GbpHeroSettings;
use App\Settings\GbpIntroSettings;
use App\Settings\GbpOverviewSettings;
use App\Settings\GbpPartnersSettings;
use App\Settings\GbpSeoSettings;
use App\Settings\GbpSnapshotSettings;
use App\Settings\GbpWhySettings;
use App\Support\GbpIcons;
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

class ManageGlobalBachelorsPathway extends Page implements HasForms
{
    use HydratesRepeaterMediaFields;
    use SavesSettingsGroups;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Global Pathways';
    protected static ?string $navigationLabel = "Global Bachelor's Pathway Page";
    protected static ?int $navigationSort = 6;
    protected static string $view = 'filament.pages.manage-global-bachelors-pathway';

    public array $data = [];

    public function mount(): void
    {
        $snapshot = app(GbpSnapshotSettings::class)->toArray();
        $snapshot['cards'] = $this->wrapNestedLists($snapshot['cards'] ?? [], 'items');
        $snapshot['ctas'] = array_values($snapshot['ctas'] ?? []);

        $intro = app(GbpIntroSettings::class)->toArray();
        $intro['paragraphs'] = $this->wrapHtmlList($intro['paragraphs'] ?? []);
        $intro['highlights'] = array_values($intro['highlights'] ?? []);

        $overview = app(GbpOverviewSettings::class)->toArray();
        $overview['paragraphs'] = $this->wrapHtmlList($overview['paragraphs'] ?? []);
        $overview['stages'] = array_values($overview['stages'] ?? []);
        $overview['panel_stats'] = array_values($overview['panel_stats'] ?? []);

        $why = app(GbpWhySettings::class)->toArray();
        $why['items'] = array_values($why['items'] ?? []);

        $explore = app(GbpExploreSettings::class)->toArray();
        $explore['cards'] = $this->wrapNestedLists($explore['cards'] ?? [], 'highlights');

        $destinations = app(GbpDestinationsSettings::class)->toArray();
        $destinations['items'] = $this->hydrateRepeaterMediaFields(
            $this->wrapNestedLists($destinations['items'] ?? [], 'points'),
            'image'
        );

        $cost = app(GbpCostSettings::class)->toArray();
        $cost['comparisons'] = array_values($cost['comparisons'] ?? []);

        $comparison = app(GbpComparisonSettings::class)->toArray();
        $comparison['cards'] = array_values(array_map(function ($card) {
            $card['bullets'] = $this->wrapStringList($card['bullets'] ?? []);
            $card['prices'] = array_values($card['prices'] ?? []);

            return $card;
        }, $comparison['cards'] ?? []));

        $areas = app(GbpAreasSettings::class)->toArray();
        $areas['cards'] = $this->wrapNestedLists($areas['cards'] ?? [], 'items');

        $partners = app(GbpPartnersSettings::class)->toArray();
        $partners['cards'] = $this->wrapNestedLists($partners['cards'] ?? [], 'best_for');

        $admission = app(GbpAdmissionSettings::class)->toArray();
        $admission['eligibility'] = $this->wrapStringList($admission['eligibility'] ?? []);
        $admission['entry_requirements'] = $this->wrapStringList($admission['entry_requirements'] ?? []);

        $documents = app(GbpDocumentsSettings::class)->toArray();
        $documents['groups'] = $this->wrapNestedLists($documents['groups'] ?? [], 'items');

        $finalCta = app(GbpFinalCtaSettings::class)->toArray();
        $finalCta['ctas'] = array_values($finalCta['ctas'] ?? []);

        $this->form->fill([
            'hero' => app(GbpHeroSettings::class)->toArray(),
            'snapshot' => $snapshot,
            'intro' => $intro,
            'overview' => $overview,
            'why' => $why,
            'explore' => $explore,
            'destinations' => $destinations,
            'cost' => $cost,
            'comparison' => $comparison,
            'areas' => $areas,
            'partners' => $partners,
            'admission' => $admission,
            'documents' => $documents,
            'finalCta' => $finalCta,
            'seo' => app(GbpSeoSettings::class)->toArray(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Tabs::make("Global Bachelor's Pathway")
                    ->tabs([
                        Tab::make('Hero')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                TextInput::make('hero.tag')->label('Eyebrow Tag'),
                                TextInput::make('hero.heading')->label('Heading'),
                                TextInput::make('hero.heading_italic')->label('Heading (Italic)'),
                                RichEditor::make('hero.sub')->label('Subheading')->columnSpanFull(),
                                TextInput::make('hero.background_image')->hidden(),
                                MediaPicker::forField('hero.background_image', 'gbp/hero')
                    ->label('Background Image')
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Snapshot')
                            ->icon('heroicon-o-squares-2x2')
                            ->schema([
                                Repeater::make('snapshot.cards')
                                    ->label('Cards')
                                    ->schema([
                                        $this->iconSelect(),
                                        TextInput::make('title')->label('Title'),
                                        $this->stringListRepeater('items', 'List Items'),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
                                Repeater::make('snapshot.ctas')
                                    ->label('Buttons')
                                    ->schema([
                                        TextInput::make('label'),
                                        TextInput::make('url'),
                                        Select::make('style')
                                            ->options([
                                                'primary' => 'Primary',
                                                'ghost' => 'Ghost',
                                            ])
                    ->default('primary'),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Intro')
                            ->icon('heroicon-o-sparkles')
                            ->schema([
                                TextInput::make('intro.tag')->label('Section Label'),
                                TextInput::make('intro.heading_line1')->label('Heading Line 1'),
                                TextInput::make('intro.heading_line2')->label('Heading Line 2'),
                                TextInput::make('intro.heading_italic')->label('Heading (Italic)'),
                                Repeater::make('intro.paragraphs')
                                    ->label('Paragraphs')
                                    ->schema([
                                        RichEditor::make('html')->label('Paragraph')->columnSpanFull(),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->columnSpanFull(),
                                Repeater::make('intro.highlights')
                                    ->label('Highlight Cards')
                                    ->schema([
                                        $this->iconSelect(),
                                        TextInput::make('label')->label('Label'),
                                        TextInput::make('value')->label('Value')->columnSpanFull(),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Overview')
                            ->icon('heroicon-o-map')
                            ->schema([
                                TextInput::make('overview.tag')->label('Section Label'),
                                TextInput::make('overview.heading')->label('Heading'),
                                TextInput::make('overview.heading_italic')->label('Heading (Italic)'),
                                Repeater::make('overview.paragraphs')
                                    ->label('Paragraphs')
                                    ->schema([
                                        RichEditor::make('html')->label('Paragraph')->columnSpanFull(),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->columnSpanFull(),
                                RichEditor::make('overview.quote')->label('Quote')->columnSpanFull(),
                                Repeater::make('overview.stages')
                                    ->label('Journey Stages')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('year')->label('Number (e.g. 01)'),
                                            TextInput::make('duration')->label('Duration'),
                                        ]),
                                        TextInput::make('title')->label('Title')->columnSpanFull(),
                                        RichEditor::make('description')->columnSpanFull(),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
                                TextInput::make('overview.panel_label')->label('Panel Label'),
                                TextInput::make('overview.panel_title')->label('Panel Title'),
                                Repeater::make('overview.panel_stats')
                                    ->label('Panel Footer Stats')
                                    ->schema([
                                        TextInput::make('number')->label('Number'),
                                        TextInput::make('label')->label('Label'),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Why Choose')
                            ->icon('heroicon-o-star')
                            ->schema([
                                TextInput::make('why.tag')->label('Section Label'),
                                TextInput::make('why.heading')->label('Heading'),
                                TextInput::make('why.heading_italic')->label('Heading (Italic)'),
                                RichEditor::make('why.quote')->label('Quote')->columnSpanFull(),
                                RichEditor::make('why.paragraph')->label('Paragraph')->columnSpanFull(),
                                Repeater::make('why.items')
                                    ->label('Cards')
                                    ->schema([
                                        $this->iconSelect(),
                                        TextInput::make('title')->label('Title'),
                                        RichEditor::make('description')->columnSpanFull(),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Explore Europe')
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                TextInput::make('explore.label')->label('Section Label'),
                                TextInput::make('explore.heading')->label('Heading'),
                                TextInput::make('explore.heading_italic')->label('Heading (Italic)'),
                                RichEditor::make('explore.sub')->label('Subheading')->columnSpanFull(),
                                Repeater::make('explore.cards')
                                    ->label('Country Cards')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('flag')->label('Flag (emoji)'),
                                            TextInput::make('country')->label('Country'),
                                        ]),
                                        TextInput::make('type')->label('Pathway Type'),
                                        TextInput::make('university')->label('University')->columnSpanFull(),
                                        $this->stringListRepeater('highlights', 'Highlights'),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['country'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Destinations')
                            ->icon('heroicon-o-map-pin')
                            ->schema([
                                TextInput::make('destinations.label')->label('Section Label'),
                                TextInput::make('destinations.heading')->label('Heading'),
                                TextInput::make('destinations.heading_italic')->label('Heading (Italic)'),
                                Repeater::make('destinations.items')
                                    ->label('Destinations')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('slug')->label('Slug (hungary, romania…)'),
                                            Select::make('position')
                                                ->options(['left' => 'Image Left', 'right' => 'Image Right'])
                                                ->default('right'),
                                        ]),
                                        TextInput::make('name')->label('Country Name'),
                                        TextInput::make('label')->label('Eyebrow Label'),
                                        TextInput::make('university')->label('University')->columnSpanFull(),
                                        RichEditor::make('description')->label('Description')->columnSpanFull(),
                                        TextInput::make('image')->hidden(),
                                        MediaPicker::forField('image', 'gbp/destinations')
                    ->label('Image')
                    ->columnSpanFull(),
                                        $this->stringListRepeater('points', 'Points'),
                                        Textarea::make('best_for')->label('Best For')->rows(2)->columnSpanFull(),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Cost & Time')
                            ->icon('heroicon-o-clock')
                            ->schema([
                                TextInput::make('cost.tag')->label('Section Label'),
                                TextInput::make('cost.heading')->label('Heading'),
                                TextInput::make('cost.heading_italic')->label('Heading (Italic)'),
                                RichEditor::make('cost.description')->label('Description')->columnSpanFull(),
                                RichEditor::make('cost.closing')->label('Closing')->columnSpanFull(),
                                Repeater::make('cost.comparisons')
                                    ->label('Comparison Rows')
                                    ->schema([
                                        TextInput::make('label')->label('Label'),
                                        TextInput::make('value')->label('Value'),
                                        Select::make('variant')
                                            ->options(['muted' => 'Muted', 'accent' => 'Accent'])
                                            ->default('muted'),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Comparison')
                            ->icon('heroicon-o-arrows-right-left')
                            ->schema([
                                RichEditor::make('comparison.heading')->label('Heading')->columnSpanFull(),
                                Repeater::make('comparison.cards')
                                    ->label('Route Cards')
                                    ->schema([
                                        Toggle::make('is_recommended')->label('Recommended card')->inline(false),
                                        TextInput::make('title')->label('Title'),
                                        TextInput::make('duration')->label('Duration'),
                                        TextInput::make('tagline')->label('Footer Tagline'),
                                        $this->stringListRepeater('bullets', 'Bullets'),
                                        Select::make('price_mode')
                                            ->label('Price Layout')
                                            ->options([
                                                'single' => 'Single value',
                                                'rows' => 'Country rows',
                                            ])
                    ->default('single'),
                                        TextInput::make('price_label')->label('Price Label'),
                                        TextInput::make('price_value')->label('Single Price Value'),
                                        Repeater::make('prices')
                                            ->label('Price Rows')
                                            ->schema([
                                                TextInput::make('country')->label('Country'),
                                                TextInput::make('amount')->label('Amount'),
                                            ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['country'] ?? null)
                    ->columnSpanFull(),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
                                TextInput::make('comparison.callout_label')->label('Callout Label'),
                                TextInput::make('comparison.callout_value')->label('Callout Value'),
                                RichEditor::make('comparison.callout_description')->label('Callout Description')->columnSpanFull(),
                            ]),

                        Tab::make('Pathway Areas')
                            ->icon('heroicon-o-briefcase')
                            ->schema([
                                TextInput::make('areas.label')->label('Section Label'),
                                TextInput::make('areas.heading')->label('Heading'),
                                TextInput::make('areas.heading_italic')->label('Heading (Italic)'),
                                RichEditor::make('areas.sub')->label('Subheading')->columnSpanFull(),
                                Repeater::make('areas.cards')
                                    ->label('Area Cards')
                                    ->schema([
                                        $this->iconSelect(),
                                        TextInput::make('title')->label('Title'),
                                        RichEditor::make('description')->columnSpanFull(),
                                        $this->stringListRepeater('items', 'Programmes'),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Partners')
                            ->icon('heroicon-o-building-office-2')
                            ->schema([
                                TextInput::make('partners.label')->label('Section Label'),
                                TextInput::make('partners.heading')->label('Heading'),
                                TextInput::make('partners.heading_italic')->label('Heading (Italic)'),
                                RichEditor::make('partners.sub')->label('Subheading')->columnSpanFull(),
                                Repeater::make('partners.cards')
                                    ->label('Partner Cards')
                                    ->schema([
                                        TextInput::make('code')->label('Code (HU, RO…)'),
                                        TextInput::make('name')->label('Name')->columnSpanFull(),
                                        RichEditor::make('description')->columnSpanFull(),
                                        $this->stringListRepeater('best_for', 'Best Suited For'),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Admission')
                            ->icon('heroicon-o-clipboard-document-check')
                            ->schema([
                                TextInput::make('admission.label')->label('Section Label'),
                                TextInput::make('admission.heading')->label('Heading'),
                                TextInput::make('admission.heading_italic')->label('Heading (Italic)'),
                                TextInput::make('admission.eligibility_title')->label('Eligibility Card Title'),
                                $this->stringListRepeater('admission.eligibility', 'Who Can Apply'),
                                TextInput::make('admission.entry_title')->label('Entry Card Title'),
                                $this->stringListRepeater('admission.entry_requirements', 'Entry Requirements'),
                                RichEditor::make('admission.note')->label('Note')->columnSpanFull(),
                            ]),

                        Tab::make('Documents')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                TextInput::make('documents.label')->label('Section Label'),
                                TextInput::make('documents.heading')->label('Heading'),
                                TextInput::make('documents.heading_italic')->label('Heading (Italic)'),
                                Repeater::make('documents.groups')
                                    ->label('Document Groups')
                                    ->schema([
                                        $this->iconSelect(),
                                        TextInput::make('title')->label('Title'),
                                        $this->stringListRepeater('items', 'Items'),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Final CTA')
                            ->icon('heroicon-o-megaphone')
                            ->schema([
                                TextInput::make('finalCta.eyebrow')->label('Eyebrow'),
                                TextInput::make('finalCta.heading')->label('Heading'),
                                TextInput::make('finalCta.heading_italic')->label('Heading (Italic)'),
                                RichEditor::make('finalCta.sub')->label('Subheading')->columnSpanFull(),
                                RichEditor::make('finalCta.description')->label('Description')->columnSpanFull(),
                                Repeater::make('finalCta.ctas')
                                    ->label('Buttons')
                                    ->schema([
                                        TextInput::make('label'),
                                        TextInput::make('url'),
                                        TextInput::make('anchor_id')->label('HTML id (optional)'),
                                        Select::make('style')
                                            ->options([
                                                'solid' => 'Solid',
                                                'outline' => 'Outline',
                                            ])
                    ->default('outline'),
                                    ])
                    ->reorderable()
                    ->collapsible()
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
                                MediaPicker::forField('seo.og_image_url', 'gbp/seo')->label('OG Image'),
                                Grid::make(2)->schema([
                                    Select::make('seo.og_type')->label('OG Type')
                                        ->options(['website' => 'Website', 'article' => 'Article', 'profile' => 'Profile']),
                                    Select::make('seo.twitter_card')->label('Twitter Card')
                                        ->options(['summary' => 'Summary', 'summary_large_image' => 'Summary Large Image']),
                                ]),
                                TextInput::make('seo.twitter_title')->label('Twitter Title')->maxLength(70),
                                Textarea::make('seo.twitter_description')->label('Twitter Description')->rows(3)->maxLength(200),
                                TextInput::make('seo.twitter_image_url')->hidden(),
                                MediaPicker::forField('seo.twitter_image_url', 'gbp/seo')->label('Twitter Image'),
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

            $existingDestinations = app(GbpDestinationsSettings::class)->toArray();

            $hero = $this->syncImageIfSelected($data['hero'] ?? [], 'background_image');

            $snapshot = $data['snapshot'] ?? [];
            $snapshot['cards'] = $this->normalizeNestedLists($snapshot['cards'] ?? [], 'items');
            $snapshot['ctas'] = array_values($snapshot['ctas'] ?? []);

            $intro = $data['intro'] ?? [];
            $intro['paragraphs'] = $this->normalizeHtmlList($intro['paragraphs'] ?? []);
            $intro['highlights'] = array_values($intro['highlights'] ?? []);

            $overview = $data['overview'] ?? [];
            $overview['paragraphs'] = $this->normalizeHtmlList($overview['paragraphs'] ?? []);
            $overview['stages'] = array_values($overview['stages'] ?? []);
            $overview['panel_stats'] = array_values($overview['panel_stats'] ?? []);

            $why = $data['why'] ?? [];
            $why['items'] = array_values($why['items'] ?? []);

            $explore = $data['explore'] ?? [];
            $explore['cards'] = $this->normalizeNestedLists($explore['cards'] ?? [], 'highlights');

            $destinations = $data['destinations'] ?? [];
            $destinations['items'] = $this->hydrateRepeaterMediaFields($destinations['items'] ?? [], 'image');
            foreach ($destinations['items'] ?? [] as &$item) {
                $item = $this->syncImageIfSelected($item, 'image');
            }
            unset($item);
            $destinations['items'] = $this->preserveRepeaterImageFields(
                $this->normalizeNestedLists($destinations['items'] ?? [], 'points'),
                $existingDestinations['items'] ?? [],
                'image'
            );

            $cost = $data['cost'] ?? [];
            $cost['comparisons'] = array_values($cost['comparisons'] ?? []);

            $comparison = $data['comparison'] ?? [];
            $comparison['cards'] = array_values(array_map(function ($card) {
                $card['bullets'] = $this->normalizeStringList($card['bullets'] ?? []);
                $card['prices'] = array_values($card['prices'] ?? []);

                return $card;
            }, $comparison['cards'] ?? []));

            $areas = $data['areas'] ?? [];
            $areas['cards'] = $this->normalizeNestedLists($areas['cards'] ?? [], 'items');

            $partners = $data['partners'] ?? [];
            $partners['cards'] = $this->normalizeNestedLists($partners['cards'] ?? [], 'best_for');

            $admission = $data['admission'] ?? [];
            $admission['eligibility'] = $this->normalizeStringList($admission['eligibility'] ?? []);
            $admission['entry_requirements'] = $this->normalizeStringList($admission['entry_requirements'] ?? []);

            $documents = $data['documents'] ?? [];
            $documents['groups'] = $this->normalizeNestedLists($documents['groups'] ?? [], 'items');

            $finalCta = $data['finalCta'] ?? [];
            $finalCta['ctas'] = array_values($finalCta['ctas'] ?? []);

            $seo = $data['seo'] ?? [];
            $seo = $this->syncImageIfSelected($seo, 'og_image_url');
            $seo = $this->syncImageIfSelected($seo, 'twitter_image_url');

            $this->saveSettingsGroup(GbpHeroSettings::class, $hero);
            $this->saveSettingsGroup(GbpSnapshotSettings::class, $snapshot);
            $this->saveSettingsGroup(GbpIntroSettings::class, $intro);
            $this->saveSettingsGroup(GbpOverviewSettings::class, $overview);
            $this->saveSettingsGroup(GbpWhySettings::class, $why);
            $this->saveSettingsGroup(GbpExploreSettings::class, $explore);
            $this->saveSettingsGroup(GbpDestinationsSettings::class, $destinations);
            $this->saveSettingsGroup(GbpCostSettings::class, $cost);
            $this->saveSettingsGroup(GbpComparisonSettings::class, $comparison);
            $this->saveSettingsGroup(GbpAreasSettings::class, $areas);
            $this->saveSettingsGroup(GbpPartnersSettings::class, $partners);
            $this->saveSettingsGroup(GbpAdmissionSettings::class, $admission);
            $this->saveSettingsGroup(GbpDocumentsSettings::class, $documents);
            $this->saveSettingsGroup(GbpFinalCtaSettings::class, $finalCta);
            $this->saveSettingsGroup(GbpSeoSettings::class, $seo);

            Notification::make()
                ->title("Global Bachelor's Pathway page saved")
                ->success()
                ->send();
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            report($e);

            Notification::make()
                ->title("Could not save Global Bachelor's Pathway page")
                ->body('Please check repeater fields and try again.')
                ->danger()
                ->send();
        }
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
            ->options(GbpIcons::options())
            ->searchable()
            ->nullable();
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
