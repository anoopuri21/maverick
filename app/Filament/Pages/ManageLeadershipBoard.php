<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\EnsuresSettingsRowsExist;
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
use Filament\Forms\Components\RichEditor;
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
    use EnsuresSettingsRowsExist;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'About Section';
    protected static ?string $navigationLabel = 'Leadership Board Page';
    protected static ?int $navigationSort = 6;
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
                                RichEditor::make('hero.description')->columnSpanFull(),
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
                                        TextInput::make('name'),
                                        TextInput::make('designation'),
                                        RichEditor::make('bio'),
                                        MediaPicker::forField('image_url', 'leadership/leaders')
                    ->label('Photo'),
                                        TextInput::make('linkedin_url')
                                            ->label('LinkedIn URL')
                                            ->default('#'),
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
        try {
            $data = $this->form->getState();

        // Denormalize MediaPicker asset ids → URLs before persisting (same
        // convention as ProgramResource). Otherwise an uploaded image never
        // writes its URL and the hero/leaders/seo image won't display.
        $hero = $data['hero'] ?? [];
        $hero = \App\Filament\Forms\Components\MediaPicker::syncFieldFromAsset($hero, 'background_image');

        $leaders = $data['leaders'] ?? [];
        foreach ($leaders['items'] ?? [] as &$item) {
            if (! is_array($item)) {
                continue;
            }
            $item = \App\Filament\Forms\Components\MediaPicker::syncFieldFromAsset($item, 'image_url');
        }
        unset($item);
        $leaders['items'] = array_values($leaders['items'] ?? []);

        $seo = $data['seo'] ?? [];
        $seo = \App\Filament\Forms\Components\MediaPicker::syncFieldFromAsset($seo, 'og_image_url');
        $seo = \App\Filament\Forms\Components\MediaPicker::syncFieldFromAsset($seo, 'twitter_image_url');

        $this->saveSettingsGroup(LeadershipHeroSettings::class, $hero);
        $this->saveSettingsGroup(LeadershipLeadersSettings::class, $leaders);
        $this->saveSettingsGroup(LeadershipSeoSettings::class, $seo);

            Notification::make()
                ->title('Leadership Board saved')
                ->success()
                ->send();
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            Notification::make()
                ->title('Could not save Leadership Board page')
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
