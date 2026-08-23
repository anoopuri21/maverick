<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\SavesSettingsGroups;
use App\Filament\Forms\Components\MediaPicker;
use App\Settings\PrivacyPageSettings;
use App\Settings\PrivacySeoSettings;
use Filament\Forms\Components\Grid;
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

class ManagePrivacyPolicy extends Page implements HasForms
{
    use SavesSettingsGroups;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationGroup = 'Site Settings';
    protected static ?string $navigationLabel = 'Privacy Policy';
    protected static ?int $navigationSort = 20;
    protected static string $view = 'filament.pages.manage-privacy-policy';

    public array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'content' => app(PrivacyPageSettings::class)->toArray(),
            'seo' => app(PrivacySeoSettings::class)->toArray(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Tabs::make('Privacy Policy')
                    ->tabs([
                        Tab::make('Hero')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                TextInput::make('content.tag')->label('Eyebrow Tag'),
                                TextInput::make('content.heading_line1')->label('Heading Line 1'),
                                TextInput::make('content.heading_italic')->label('Heading (Italic)'),
                                RichEditor::make('content.description')->label('Hero Description')->columnSpanFull(),
                                MediaPicker::forField('content.background_image', 'privacy/hero')
                                    ->label('Hero Background Image')
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('Content')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                MediaPicker::forField('content.center_image', 'privacy/center')
                                    ->label('Center Image')
                                    ->columnSpanFull(),
                                TextInput::make('content.center_image_alt')
                                    ->label('Center Image Alt Text')
                                    ->columnSpanFull(),
                                RichEditor::make('content.body')
                                    ->label('Policy Body')
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
                                TextInput::make('seo.og_title')->label('OG Title'),
                                Textarea::make('seo.og_description')->label('OG Description')->rows(2),
                                MediaPicker::forField('seo.og_image_url', 'seo/og-images')
                                    ->label('OG Image')
                                    ->columnSpanFull(),
                                TextInput::make('seo.twitter_title')->label('Twitter Title'),
                                Textarea::make('seo.twitter_description')->label('Twitter Description')->rows(2),
                                MediaPicker::forField('seo.twitter_image_url', 'seo/twitter-images')
                                    ->label('Twitter Image')
                                    ->columnSpanFull(),
                                Textarea::make('seo.schema_json')->label('Schema JSON')->rows(4),
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

            $content = $data['content'] ?? [];
            $content = MediaPicker::syncFieldFromAsset($content, 'background_image');
            $content = MediaPicker::syncFieldFromAsset($content, 'center_image');

            $seo = $data['seo'] ?? [];
            $seo = MediaPicker::syncFieldFromAsset($seo, 'og_image_url');
            $seo = MediaPicker::syncFieldFromAsset($seo, 'twitter_image_url');

            $this->saveSettingsGroup(PrivacyPageSettings::class, $content);
            $this->saveSettingsGroup(PrivacySeoSettings::class, $seo);

            Notification::make()
                ->title('Privacy Policy saved')
                ->success()
                ->send();
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            Notification::make()
                ->title('Could not save Privacy Policy')
                ->danger()
                ->send();
        }
    }
}
