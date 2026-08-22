<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\EnsuresSettingsRowsExist;
use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Settings\FacultyVoicePageSettings;
use App\Settings\FacultyVoiceSeoSettings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageFacultyVoicePage extends Page implements HasForms
{
    use HandlesCloudinaryImageFields;
    use EnsuresSettingsRowsExist;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-microphone';
    protected static ?string $navigationGroup = 'Insights';
    protected static ?string $navigationLabel = 'Faculty Voice Page';
    protected static ?int $navigationSort = 3;
    protected static string $view = 'filament.pages.manage-faculty-voice-page';

    public array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'hero' => app(FacultyVoicePageSettings::class)->toArray(),
            'seo' => app(FacultyVoiceSeoSettings::class)->toArray(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Tabs::make('Faculty Voice Page')
                    ->tabs([
                        Tab::make('Hero')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Section::make()
                                    ->description('Faculty Insights are managed under Insights → Faculty Insights')
                                    ->schema([
                                        TextInput::make('hero.hero_tag')->label('Eyebrow Tag'),
                                        TextInput::make('hero.hero_heading')->label('Heading'),
                                        TextInput::make('hero.hero_heading_italic')->label('Heading (Italic)'),
                                        RichEditor::make('hero.hero_description')->columnSpanFull(),
                                        MediaPicker::forField('hero.hero_background_image', 'faculty-voice/hero')
                    ->label('Background Image')
                    ->columnSpanFull(),
                                        TextInput::make('hero.empty_message')->label('Empty State Message')->columnSpanFull(),
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
                                MediaPicker::forField('seo.og_image_url', 'faculty-voice/seo')->label('OG Image'),
                                Grid::make(2)->schema([
                                    Select::make('seo.og_type')->label('OG Type')
                                        ->options(['website' => 'Website', 'article' => 'Article', 'profile' => 'Profile']),
                                    Select::make('seo.twitter_card')->label('Twitter Card')
                                        ->options(['summary' => 'Summary', 'summary_large_image' => 'Summary Large Image']),
                                ]),
                                TextInput::make('seo.twitter_title')->label('Twitter Title')->maxLength(70),
                                Textarea::make('seo.twitter_description')->label('Twitter Description')->rows(3)->maxLength(200),
                                MediaPicker::forField('seo.twitter_image_url', 'faculty-voice/seo')->label('Twitter Image'),
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

            $hero = $data['hero'] ?? [];
            $hero = \App\Filament\Forms\Components\MediaPicker::syncFieldFromAsset($hero, 'hero_background_image');

            $seo = $data['seo'] ?? [];
            $seo = \App\Filament\Forms\Components\MediaPicker::syncFieldFromAsset($seo, 'og_image_url');
            $seo = \App\Filament\Forms\Components\MediaPicker::syncFieldFromAsset($seo, 'twitter_image_url');

            $this->saveSettingsGroup(FacultyVoicePageSettings::class, $hero);
            $this->saveSettingsGroup(FacultyVoiceSeoSettings::class, $seo);

            Notification::make()
                ->title('Faculty Voice Page saved')
                ->success()
                ->send();
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            Notification::make()
                ->title('Could not save Faculty Voice Page')
                ->danger()
                ->send();
        }
    }

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