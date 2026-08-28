<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\SavesSettingsGroups;
use App\Filament\Forms\Components\MediaPicker;
use App\Settings\GlobalPartnersBenefitsSettings;
use App\Settings\GlobalPartnersCardsSettings;
use App\Settings\GlobalPartnersHeroSettings;
use App\Settings\GlobalPartnersJourneySettings;
use App\Settings\GlobalPartnersMapSettings;
use App\Settings\GlobalPartnersOverviewSettings;
use App\Settings\GlobalPartnersSeoSettings;
use App\Settings\GlobalPartnersWhySettings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageGlobalUniversityPartners extends Page implements HasForms
{
    use SavesSettingsGroups;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationGroup = 'About Section';
    protected static ?string $navigationLabel = 'Page Content';
    protected static ?int $navigationSort = 7;
    protected static string $view = 'filament.pages.manage-global-university-partners';

    public static function shouldRegisterNavigation(): bool
    {
        // Rendered inside ManageGlobalPartnersPage ("Page Content" tab).
        return false;
    }

    public array $data = [];

    public function mount(): void
    {
        $why = app(GlobalPartnersWhySettings::class)->toArray();
        $why['items'] = array_values($why['items'] ?? []);

        $benefits = app(GlobalPartnersBenefitsSettings::class)->toArray();
        $benefits['items'] = array_values($benefits['items'] ?? []);

        $this->form->fill([
            'hero' => app(GlobalPartnersHeroSettings::class)->toArray(),
            'overview' => app(GlobalPartnersOverviewSettings::class)->toArray(),
            'cards' => app(GlobalPartnersCardsSettings::class)->toArray(),
            'map' => app(GlobalPartnersMapSettings::class)->toArray(),
            'why' => $why,
            'benefits' => $benefits,
            'journey' => app(GlobalPartnersJourneySettings::class)->toArray(),
            'seo' => app(GlobalPartnersSeoSettings::class)->toArray(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Tabs::make('Global University Partners')
                    ->tabs([
                        Tab::make('Hero')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                TextInput::make('hero.tag')->label('Eyebrow Tag'),
                                TextInput::make('hero.heading_line1')->label('Heading Line 1'),
                                TextInput::make('hero.heading_italic')->label('Heading (Italic)'),
                                RichEditor::make('hero.description')->columnSpanFull(),
                                TextInput::make('hero.scroll_hint')->label('Scroll Hint'),
                                MediaPicker::forField('hero.background_image', 'global-partners/hero')
                    ->label('Background Image')
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Overview')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                TextInput::make('overview.tag')->label('Section Label'),
                                TextInput::make('overview.heading')->label('Heading'),
                                TextInput::make('overview.heading_italic')->label('Heading (Italic)'),
                                RichEditor::make('overview.paragraph')->columnSpanFull(),
                                MediaPicker::forField('overview.image', 'global-partners/overview')
                    ->label('Image')
                    ->columnSpanFull(),
                                TextInput::make('overview.image_alt')->label('Image Alt Text'),
                            ]),

                        Tab::make('Partner Cards Heading')
                            ->icon('heroicon-o-building-library')
                            ->schema([
                                TextInput::make('cards.label')->label('Section Label'),
                                TextInput::make('cards.heading')->label('Heading'),
                                TextInput::make('cards.heading_italic')->label('Heading (Italic)'),
                                Textarea::make('cards.subheading')->rows(3)->columnSpanFull(),
                                TextInput::make('cards.cta_label')->label('University Card CTA Label'),
                                TextInput::make('cards.recognition_label')->label('Recognition Label'),
                            ]),

                        Tab::make('Map Section')
                            ->icon('heroicon-o-map')
                            ->schema([
                                TextInput::make('map.label')->label('Section Label'),
                                TextInput::make('map.heading_line1')->label('Heading Line 1'),
                                TextInput::make('map.heading_line2')->label('Heading Line 2'),
                            ]),

                        Tab::make('Why Partnerships')
                            ->icon('heroicon-o-heart')
                            ->schema([
                                TextInput::make('why.tag')->label('Section Label'),
                                TextInput::make('why.heading')->label('Heading'),
                                TextInput::make('why.heading_italic')->label('Heading (Italic)'),
                                RichEditor::make('why.quote')->columnSpanFull(),
                                Repeater::make('why.items')
                                    ->schema([
                                        Select::make('icon')
                                            ->options($this->lucideIconOptions())
                                            ->searchable()
                                            ,
                                        TextInput::make('title'),
                                        RichEditor::make('description'),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Student Benefits')
                            ->icon('heroicon-o-check-badge')
                            ->schema([
                                TextInput::make('benefits.tag')->label('Section Label'),
                                TextInput::make('benefits.heading')->label('Heading'),
                                TextInput::make('benefits.heading_italic')->label('Heading (Italic)'),
                                Grid::make(2)->schema([
                                    MediaPicker::forField('benefits.main_image', 'global-partners/benefits')
                    ->label('Main Image'),
                                    TextInput::make('benefits.main_image_alt')->label('Main Image Alt'),
                                    MediaPicker::forField('benefits.secondary_image', 'global-partners/benefits')
                    ->label('Secondary Image'),
                                    TextInput::make('benefits.secondary_image_alt')->label('Secondary Image Alt'),
                                ]),
                                Grid::make(2)->schema([
                                    TextInput::make('benefits.stat_number')->label('Floating Stat Number'),
                                    TextInput::make('benefits.stat_label')->label('Floating Stat Label'),
                                ]),
                                Repeater::make('benefits.items')
                                    ->schema([
                                        TextInput::make('title'),
                                        RichEditor::make('description'),
                                        Toggle::make('highlighted')->label('Highlighted'),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Journey Heading')
                            ->icon('heroicon-o-camera')
                            ->schema([
                                TextInput::make('journey.label')->label('Section Label'),
                                TextInput::make('journey.heading')->label('Heading'),
                                TextInput::make('journey.heading_italic')->label('Heading (Italic)'),
                                Textarea::make('journey.subheading')->rows(3)->columnSpanFull(),
                                TextInput::make('journey.filter_all_label')->label('Gallery Filter: All'),
                            ]),

                        Tab::make('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                TextInput::make('seo.meta_title')->label('Meta Title')->maxLength(60),
                                Textarea::make('seo.meta_description')->label('Meta Description')->rows(3)->maxLength(160),
                                Textarea::make('seo.meta_keywords')->label('Meta Keywords')->rows(2),
                                Grid::make(2)->schema([
                                    TextInput::make('seo.canonical_url')->label('Canonical URL')->nullable(),
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
                                MediaPicker::forField('seo.og_image_url', 'global-partners/seo')->label('OG Image'),
                                Grid::make(2)->schema([
                                    Select::make('seo.og_type')->label('OG Type')
                                        ->options([
                                            'website' => 'Website',
                                            'article' => 'Article',
                                            'profile' => 'Profile',
                                        ]),
                                    Select::make('seo.twitter_card')->label('Twitter Card')
                                        ->options([
                                            'summary' => 'Summary',
                                            'summary_large_image' => 'Summary Large Image',
                                        ]),
                                ]),
                                TextInput::make('seo.twitter_title')->label('Twitter Title')->maxLength(70),
                                Textarea::make('seo.twitter_description')->label('Twitter Description')->rows(3)->maxLength(200),
                                MediaPicker::forField('seo.twitter_image_url', 'global-partners/seo')->label('Twitter Image'),
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

            $this->saveSettingsGroup(GlobalPartnersHeroSettings::class, $data['hero'] ?? []);
            $this->saveSettingsGroup(GlobalPartnersOverviewSettings::class, $data['overview'] ?? []);
            $this->saveSettingsGroup(GlobalPartnersCardsSettings::class, $data['cards'] ?? []);
            $this->saveSettingsGroup(GlobalPartnersMapSettings::class, $data['map'] ?? []);

            $why = $data['why'] ?? [];
            $why['items'] = settings_array($why['items'] ?? []);
            $this->saveSettingsGroup(GlobalPartnersWhySettings::class, $why);

            $benefits = $data['benefits'] ?? [];
            $benefits['items'] = settings_array($benefits['items'] ?? []);
            $this->saveSettingsGroup(GlobalPartnersBenefitsSettings::class, $benefits);

            $this->saveSettingsGroup(GlobalPartnersJourneySettings::class, $data['journey'] ?? []);

            $seo = $data['seo'] ?? [];
            unset($seo['og_image_url_asset_id'], $seo['twitter_image_url_asset_id']);
            $this->saveSettingsGroup(GlobalPartnersSeoSettings::class, $seo);

            Notification::make()
                ->title('Global University Partners saved')
                ->success()
                ->send();
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            Notification::make()
                ->title('Could not save Global University Partners page')
                ->danger()
                ->send();
        }
    }

    /** @return array<string, string> */
    protected function lucideIconOptions(): array
    {
        return [
            'graduation-cap' => 'graduation-cap — Graduation',
            'globe' => 'globe — Global',
            'book-open' => 'book-open — Curriculum',
            'award' => 'award — Recognition',
            'rocket' => 'rocket — Career',
            'users' => 'users — Teams',
            'trending-up' => 'trending-up — Growth',
            'laptop' => 'laptop — Flexible learning',
            'sparkles' => 'sparkles — Highlights',
            'shield' => 'shield — Accreditation',
            'briefcase' => 'briefcase — Business',
            'target' => 'target — Goals',
            'lightbulb' => 'lightbulb — Innovation',
            'heart-handshake' => 'heart-handshake — Support',
        ];
    }
}
