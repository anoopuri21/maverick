<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Settings\HomepageSeoSettings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageHomepageSeo extends SettingsPage
{
    use HandlesCloudinaryImageFields;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Homepage';
    protected static ?string $navigationLabel = 'Homepage SEO';
    protected static ?int $navigationSort = 99;
    protected static string $settings = HomepageSeoSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Basic SEO')->description('These power the <title> and meta tags on the homepage.')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(60)
                            ->helperText('Best under 60 characters.'),
                        TextInput::make('canonical_url')
                            ->label('Canonical URL')
                            ->url()
                            ->helperText('Leave empty to auto-use the current page URL.'),
                    ]),
                    Textarea::make('meta_description')
                        ->label('Meta Description')
                        ->rows(3)
                        ->maxLength(160)
                        ->helperText('Best under 160 characters.'),
                    Textarea::make('meta_keywords')
                        ->label('Meta Keywords')
                        ->rows(2)
                        ->helperText('Comma-separated keywords.'),
                    Select::make('robots')
                        ->label('Robots')
                        ->options([
                            'index, follow' => 'index, follow',
                            'noindex, follow' => 'noindex, follow',
                            'index, nofollow' => 'index, nofollow',
                            'noindex, nofollow' => 'noindex, nofollow',
                        ])
                        ->default('index, follow'),
                ]),

            Section::make('Open Graph')->description('Used when the homepage is shared on Facebook, WhatsApp, LinkedIn etc.')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('og_title')->label('OG Title')->maxLength(60),
                        TextInput::make('og_description')->label('OG Description')->maxLength(200),
                    ]),
                    Grid::make(2)->schema([
                        MediaPicker::forField('og_image_url', 'homepage-seo/og')
                            ->label('OG Image (Media Library)')
                            ->helperText('Upload from library (priority) or use the URL field below.'),
                        TextInput::make('og_image_url_input')->label('Or OG Image URL')->url(),
                    ]),
                    Select::make('og_type')
                        ->label('OG Type')
                        ->options([
                            'website' => 'website',
                            'article' => 'article',
                            'profile' => 'profile',
                        ])
                        ->default('website'),
                ]),

            Section::make('Twitter Card')->description('Used when the homepage is shared on X (Twitter).')
                ->schema([
                    Select::make('twitter_card')
                        ->label('Twitter Card')
                        ->options([
                            'summary_large_image' => 'summary_large_image',
                            'summary' => 'summary',
                            'app' => 'app',
                        ])
                        ->default('summary_large_image'),
                    Grid::make(2)->schema([
                        TextInput::make('twitter_title')->label('Twitter Title')->maxLength(70),
                        TextInput::make('twitter_description')->label('Twitter Description')->maxLength(200),
                    ]),
                    Grid::make(2)->schema([
                        MediaPicker::forField('twitter_image_url', 'homepage-seo/twitter')
                            ->label('Twitter Image (Media Library)')
                            ->helperText('Upload from library (priority) or use the URL field below.'),
                        TextInput::make('twitter_image_url_input')->label('Or Twitter Image URL')->url(),
                    ]),
                ]),

            Section::make('Advanced')->description('Schema.org JSON-LD and custom scripts for analytics / pixels.')
                ->schema([
                    Textarea::make('schema_json')
                        ->label('Schema.org JSON-LD')
                        ->rows(5)
                        ->helperText('Paste a valid JSON-LD block (including the <script> tags). Rendered only when non-empty.'),
                    Textarea::make('custom_head_scripts')
                        ->label('Custom Head Scripts')
                        ->rows(3)
                        ->helperText('Injected into <head>. Rendered only when non-empty.'),
                    Textarea::make('custom_body_scripts')
                        ->label('Custom Body Scripts')
                        ->rows(3)
                        ->helperText('Injected into the page scripts. Rendered only when non-empty.'),
                ]),
        ]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = MediaPicker::syncFieldFromAsset($data, 'og_image_url');
        if (empty($data['og_image_url']) && ! empty($data['og_image_url_input'])) {
            $data['og_image_url'] = $data['og_image_url_input'];
        }
        unset($data['og_image_url_input']);

        $data = MediaPicker::syncFieldFromAsset($data, 'twitter_image_url');
        if (empty($data['twitter_image_url']) && ! empty($data['twitter_image_url_input'])) {
            $data['twitter_image_url'] = $data['twitter_image_url_input'];
        }
        unset($data['twitter_image_url_input']);

        return $data;
    }
}
