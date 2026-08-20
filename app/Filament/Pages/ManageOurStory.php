<?php

namespace App\Filament\Pages;

use App\Settings\OurStoryBeginningSettings;
use App\Settings\OurStoryHeroSettings;
use App\Settings\OurStoryImpactSettings;
use App\Settings\OurStorySeoSettings;
use App\Settings\OurStoryTodaySettings;
use App\Settings\OurStoryVisionSettings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use App\Filament\Forms\Components\MediaPicker;

class ManageOurStory extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'About Section';
    protected static ?string $navigationLabel = 'Our Story Page';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.manage-our-story';

    public array $data = [];

    public function mount(): void
    {
        // Load all our-story settings + seo into the form state.
        $this->form->fill([
            'hero' => app(OurStoryHeroSettings::class)->toArray(),
            'beginning' => app(OurStoryBeginningSettings::class)->toArray(),
            'today' => app(OurStoryTodaySettings::class)->toArray(),
            'impact' => app(OurStoryImpactSettings::class)->toArray(),
            'vision' => app(OurStoryVisionSettings::class)->toArray(),
            'seo' => app(OurStorySeoSettings::class)->toArray(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Tabs::make('Our Story')
                    ->tabs([
                        Tab::make('Hero')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                TextInput::make('hero.heading')->label('Heading'),
                                TextInput::make('hero.subtitle')->label('Subtitle'),
                                RichEditor::make('hero.description')->label('Description'),
                                TextInput::make('hero.cta_label')->label('CTA Label'),
                                TextInput::make('hero.cta_url')->label('CTA URL'),
                                MediaPicker::forField('hero.image_url', 'our-story/hero')->label('Hero Image'),
                            ]),

                        Tab::make('Beginning')
                            ->icon('heroicon-o-play')
                            ->schema([
                                TextInput::make('beginning.badge')->label('Badge'),
                                TextInput::make('beginning.heading')->label('Heading'),
                                RichEditor::make('beginning.paragraph_1')->label('Paragraph 1'),
                                RichEditor::make('beginning.paragraph_2')->label('Paragraph 2'),
                                MediaPicker::forField('beginning.image_url', 'our-story/beginning')->label('Image'),
                            ]),

                        Tab::make('Today')
                            ->icon('heroicon-o-clock')
                            ->schema([
                                TextInput::make('today.badge')->label('Badge'),
                                TextInput::make('today.heading')->label('Heading'),
                                RichEditor::make('today.description')->label('Description'),
                                MediaPicker::forField('today.image_url', 'our-story/today')->label('Image'),
                            ]),

                        Tab::make('Impact')
                            ->icon('heroicon-o-chart-bar')
                            ->schema([
                                TextInput::make('impact.heading')->label('Heading'),
                                RichEditor::make('impact.description')->label('Description'),
                                Grid::make(4)->schema([
                                    TextInput::make('impact.stat_1_value')->label('Stat 1 Value'),
                                    TextInput::make('impact.stat_1_label')->label('Stat 1 Label'),
                                    TextInput::make('impact.stat_2_value')->label('Stat 2 Value'),
                                    TextInput::make('impact.stat_2_label')->label('Stat 2 Label'),
                                    TextInput::make('impact.stat_3_value')->label('Stat 3 Value'),
                                    TextInput::make('impact.stat_3_label')->label('Stat 3 Label'),
                                    TextInput::make('impact.stat_4_value')->label('Stat 4 Value'),
                                    TextInput::make('impact.stat_4_label')->label('Stat 4 Label'),
                                ]),
                            ]),

                        Tab::make('Vision')
                            ->icon('heroicon-o-eye')
                            ->schema([
                                TextInput::make('vision.heading')->label('Heading'),
                                RichEditor::make('vision.description')->label('Description'),
                                MediaPicker::forField('vision.background_image_url', 'our-story/vision')->label('Background Image'),
                                TextInput::make('vision.cta_label')->label('CTA Label'),
                                TextInput::make('vision.cta_url')->label('CTA URL'),
                            ]),

                        Tab::make('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                TextInput::make('seo.meta_title')->label('Meta Title')->maxLength(60),
                                Textarea::make('seo.meta_description')->label('Meta Description')->rows(3)->maxLength(160),
                                Textarea::make('seo.meta_keywords')->label('Meta Keywords')->rows(2),
                                Grid::make(2)->schema([
                                    TextInput::make('seo.canonical_url')->label('Canonical URL')->url(),
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
                                TextInput::make('seo.og_image_url')->label('OG Image URL')->url(),
                                MediaPicker::forField('seo.og_image_url', 'our-story/seo')->label('OG Image'),
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
                                TextInput::make('seo.twitter_image_url')->label('Twitter Image URL')->url(),
                                MediaPicker::forField('seo.twitter_image_url', 'our-story/seo')->label('Twitter Image'),
                                Textarea::make('seo.schema_json')->label('Schema.org JSON-LD')->rows(6)->helperText('Must be valid JSON-LD'),
                                Textarea::make('seo.custom_head_scripts')->label('Custom Head Scripts')->rows(4),
                                Textarea::make('seo.custom_body_scripts')->label('Custom Body Scripts')->rows(4),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Persist each settings group.
        app(OurStoryHeroSettings::class)->fill($data['hero'] ?? [])->save();
        app(OurStoryBeginningSettings::class)->fill($data['beginning'] ?? [])->save();
        app(OurStoryTodaySettings::class)->fill($data['today'] ?? [])->save();
        app(OurStoryImpactSettings::class)->fill($data['impact'] ?? [])->save();
        app(OurStoryVisionSettings::class)->fill($data['vision'] ?? [])->save();

        // SEO: strip transient media-asset keys before saving settings.
        $seo = $data['seo'] ?? [];
        unset($seo['og_image_url_asset_id'], $seo['twitter_image_url_asset_id']);
        app(OurStorySeoSettings::class)->fill($seo)->save();

        Notification::make()
            ->title('Our Story saved')
            ->success()
            ->send();
    }
}
