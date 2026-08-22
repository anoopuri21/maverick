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
use App\Filament\Concerns\EnsuresSettingsRowsExist;
use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;

class ManageOurStory extends Page implements HasForms
{
    use InteractsWithForms;
    use HandlesCloudinaryImageFields;
    use EnsuresSettingsRowsExist;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'About Section';
    protected static ?string $navigationLabel = 'Our Story Page';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.manage-our-story';

    public array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'hero' => safe_settings(OurStoryHeroSettings::class)->toArray(),
            'beginning' => safe_settings(OurStoryBeginningSettings::class)->toArray(),
            'today' => safe_settings(OurStoryTodaySettings::class)->toArray(),
            'impact' => safe_settings(OurStoryImpactSettings::class)->toArray(),
            'vision' => safe_settings(OurStoryVisionSettings::class)->toArray(),
            'seo' => safe_settings(OurStorySeoSettings::class)->toArray(),
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
                                    TextInput::make('seo.canonical_url')->label('Canonical URL')->url()->nullable(),
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
                                TextInput::make('seo.og_image_url')->label('OG Image URL')->url()->nullable(),
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
                                TextInput::make('seo.twitter_image_url')->label('Twitter Image URL')->url()->nullable(),
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
        try {
            $data = $this->form->getState();

            $hero = MediaPicker::syncFieldFromAsset($data['hero'] ?? [], 'image_url');
            $beginning = MediaPicker::syncFieldFromAsset($data['beginning'] ?? [], 'image_url');
            $today = MediaPicker::syncFieldFromAsset($data['today'] ?? [], 'image_url');
            $impact = $data['impact'] ?? [];
            $vision = MediaPicker::syncFieldFromAsset($data['vision'] ?? [], 'image_url');
            $seo = MediaPicker::syncFieldFromAsset($data['seo'] ?? [], 'og_image_url');
            $seo = MediaPicker::syncFieldFromAsset($seo, 'twitter_image_url');
            unset($seo['og_image_url_asset_id'], $seo['twitter_image_url_asset_id']);

            $this->saveSettingsGroup(OurStoryHeroSettings::class, $hero);
            $this->saveSettingsGroup(OurStoryBeginningSettings::class, $beginning);
            $this->saveSettingsGroup(OurStoryTodaySettings::class, $today);
            $this->saveSettingsGroup(OurStoryImpactSettings::class, $impact);
            $this->saveSettingsGroup(OurStoryVisionSettings::class, $vision);
            $this->saveSettingsGroup(OurStorySeoSettings::class, $seo);

            Notification::make()
                ->title('Our Story saved')
                ->success()
                ->send();
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            Notification::make()
                ->title('Could not save Our Story page')
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
}
