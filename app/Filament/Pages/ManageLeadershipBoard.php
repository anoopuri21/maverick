<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Settings\LeadershipHeroSettings;
use App\Settings\LeadershipLeadersSettings;
use App\Settings\LeadershipSeoSettings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
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

class ManageLeadershipBoard extends Page implements HasForms
{
    use HandlesCloudinaryImageFields;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'About';
    protected static ?string $navigationLabel = 'Leadership Board Page';
    protected static ?int $navigationSort = 5;
    protected static string $view = 'filament.pages.manage-leadership-board';

    public array $data = [];

    public function mount(): void
    {
        $leaders = app(LeadershipLeadersSettings::class)->toArray();
        $leaders['items'] = array_values($leaders['items'] ?? []);

        $this->form->fill([
            'hero' => app(LeadershipHeroSettings::class)->toArray(),
            'leaders' => $leaders,
            'seo' => app(LeadershipSeoSettings::class)->toArray(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Tabs::make('Leadership Board')
                    ->tabs([
                        Tab::make('Hero')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                TextInput::make('hero.tag')->label('Eyebrow Tag'),
                                TextInput::make('hero.heading_line1')->label('Heading Line 1'),
                                TextInput::make('hero.heading_italic')->label('Heading (Italic)'),
                                Textarea::make('hero.description')->rows(4)->columnSpanFull(),
                                MediaPicker::forField('hero.background_image', 'leadership/hero')
                                    ->label('Background Image')
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('Leaders')
                            ->icon('heroicon-o-user-group')
                            ->schema([
                                TextInput::make('leaders.label')->label('Section Label'),
                                TextInput::make('leaders.heading')->label('Heading'),
                                TextInput::make('leaders.heading_italic')->label('Heading (Italic)'),
                                Textarea::make('leaders.subheading')->rows(3)->columnSpanFull(),
                                Repeater::make('leaders.items')
                                    ->label('Leadership Cards')
                                    ->schema([
                                        TextInput::make('name')->required(),
                                        TextInput::make('designation')->required(),
                                        Textarea::make('bio')->rows(3)->required(),
                                        MediaPicker::forField('image_url', 'leadership/leaders')
                                            ->label('Photo'),
                                        TextInput::make('linkedin_url')
                                            ->label('LinkedIn URL')
                                            ->helperText('Leave as # or empty if not available.')
                                            ->default('#')
                                            ->rules(['nullable', 'string', 'max:255']),
                                    ])
                                    ->reorderable()
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                                    ->columnSpanFull(),
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
                                MediaPicker::forField('seo.og_image_url', 'leadership/seo')->label('OG Image'),
                                Grid::make(2)->schema([
                                    Select::make('seo.og_type')->label('OG Type')
                                        ->options(['website' => 'Website', 'article' => 'Article', 'profile' => 'Profile']),
                                    Select::make('seo.twitter_card')->label('Twitter Card')
                                        ->options(['summary' => 'Summary', 'summary_large_image' => 'Summary Large Image']),
                                ]),
                                TextInput::make('seo.twitter_title')->label('Twitter Title')->maxLength(70),
                                Textarea::make('seo.twitter_description')->label('Twitter Description')->rows(3)->maxLength(200),
                                MediaPicker::forField('seo.twitter_image_url', 'leadership/seo')->label('Twitter Image'),
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
        $data = $this->form->getState();

        $this->saveSettingsGroup(LeadershipHeroSettings::class, $data['hero'] ?? []);

        $leaders = $data['leaders'] ?? [];
        $leaders['items'] = array_values($leaders['items'] ?? []);
        $this->saveSettingsGroup(LeadershipLeadersSettings::class, $leaders);

        $seo = $data['seo'] ?? [];
        unset($seo['og_image_url_asset_id'], $seo['twitter_image_url_asset_id']);
        $this->saveSettingsGroup(LeadershipSeoSettings::class, $seo);

        Notification::make()
            ->title('Leadership Board saved')
            ->success()
            ->send();
    }

    /** @param  class-string  $settingsClass */
    protected function saveSettingsGroup(string $settingsClass, array $payload): void
    {
        $settings = app($settingsClass);
        // Merge missing reflected properties from the CURRENT settings values
        // (not class defaults) so Spatie never throws MissingSettings AND any
        // untouched field (e.g. a nested MediaPicker URL) keeps its DB value.
        $payload = $this->ensureAllSettingsProperties($settings, $payload);
        $payload = $this->preserveExistingImageFields($payload, $settings);
        $settings->fill($payload)->save();
    }

    /**
     * Merge any missing settings properties from the current settings instance
     * so Spatie's save() never throws MissingSettings, regardless of what the
     * form submitted. Generic — works for any Spatie Settings class and keeps
     * untouched fields (e.g. nested MediaPicker URLs) intact.
     *
     * @param  object  $settings  Spatie Settings instance (already loaded)
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
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
}
