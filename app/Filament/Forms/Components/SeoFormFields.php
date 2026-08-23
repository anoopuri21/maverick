<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Concerns\HandlesCloudinaryImageFields;

class SeoFormFields
{
    use HandlesCloudinaryImageFields;

    /**
     * Unprefixed SEO fields for Spatie SettingsPage classes.
     */
    public static function forSettingsPage(string $mediaFolder = 'seo', string $pageLabel = 'this page'): array
    {
        return [
            Section::make('Basic SEO')->description("These power the <title> and meta tags on {$pageLabel}.")
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(60)
                            ->helperText('Best under 60 characters.'),
                        TextInput::make('canonical_url')
                            ->label('Canonical URL')
                            ->url()->nullable()
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

            Section::make('Open Graph')->description("Used when {$pageLabel} is shared on Facebook, WhatsApp, LinkedIn etc.")
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('og_title')->label('OG Title')->maxLength(60),
                        TextInput::make('og_description')->label('OG Description')->maxLength(200),
                    ]),
                    Grid::make(2)->schema([
                        MediaPicker::forField('og_image_url', $mediaFolder.'/og')
                    ->label('OG Image (Media Library)')
                    ->helperText('Upload from library (priority) or use the URL field below.'),
                        TextInput::make('og_image_url_input')->label('Or OG Image URL')->url()->nullable(),
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

            Section::make('Twitter Card')->description("Used when {$pageLabel} is shared on X (Twitter).")
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
                        MediaPicker::forField('twitter_image_url', $mediaFolder.'/twitter')
                    ->label('Twitter Image (Media Library)')
                    ->helperText('Upload from library (priority) or use the URL field below.'),
                        TextInput::make('twitter_image_url_input')->label('Or Twitter Image URL')->url()->nullable(),
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
        ];
    }

    public static function syncSettingsImages(array $data): array
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

    /**
     * Get all SEO form fields as an array.
     * Use as: ...SeoFormFields::make()
     */
    public static function make(): array
    {
        return [
            Tabs::make('SEO Settings')
                ->tabs([
                    // Basic SEO Tab
                    Tab::make('Basic SEO')
                        ->icon('heroicon-o-magnifying-glass')
                        ->schema([
                            TextInput::make('seo.meta_title')
                                ->label('Meta Title')
                                ->maxLength(60)
                                ->helperText('Optimal: 50-60 characters. Shown in browser tab & search results.')
                                ->columnSpanFull(),

                            Textarea::make('seo.meta_description')
                                ->label('Meta Description')
                                ->maxLength(160)
                                ->rows(3)
                                ->helperText('Optimal: 150-160 characters. Shown in Google search snippets.')
                                ->columnSpanFull(),

                            Textarea::make('seo.meta_keywords')
                                ->label('Meta Keywords')
                                ->rows(2)
                                ->helperText('Comma separated. E.g., MBA, business school, London')
                                ->columnSpanFull(),

                            Grid::make(2)->schema([
                                TextInput::make('seo.canonical_url')
                                    ->label('Canonical URL')
                                    ->url()->nullable()
                                    ->helperText('Leave blank to use current page URL.'),

                                Select::make('seo.robots')
                                    ->label('Robots Meta')
                                    ->options([
                                        'index, follow' => 'Index, Follow (Default)',
                                        'noindex, follow' => 'No Index, Follow',
                                        'index, nofollow' => 'Index, No Follow',
                                        'noindex, nofollow' => 'No Index, No Follow',
                                    ])
                    ->default('index, follow'),
                            ]),
                        ]),

                    // Open Graph Tab
                    Tab::make('Social Sharing (OG)')
                        ->icon('heroicon-o-share')
                        ->schema([
                            TextInput::make('seo.og_title')
                                ->label('OG Title')
                                ->maxLength(60)
                                ->helperText('Title when shared on Facebook, LinkedIn, WhatsApp. Leave blank to use Meta Title.')
                                ->columnSpanFull(),

                            Textarea::make('seo.og_description')
                                ->label('OG Description')
                                ->maxLength(200)
                                ->rows(3)
                                ->helperText('Description for social shares. Leave blank to use Meta Description.')
                                ->columnSpanFull(),

                            TextInput::make('seo.og_image_url')
                                ->label('OG Image URL')
                                ->url()->nullable()
                                ->helperText('Recommended: 1200x630px. Used for Facebook, LinkedIn shares. Or choose from the media library below.')
                                ->columnSpanFull(),

                            MediaPicker::forField('seo.og_image_url', 'seo/og-images')
                    ->label('OG Image'),

                            Select::make('seo.og_type')
                                ->label('OG Type')
                                ->options([
                                    'website' => 'Website',
                                    'article' => 'Article',
                                    'product' => 'Product',
                                    'profile' => 'Profile',
                                ])
                    ->default('website'),
                        ]),

                    // Twitter Card Tab
                    Tab::make('Twitter')
                        ->icon('heroicon-o-hashtag')
                        ->schema([
                            Select::make('seo.twitter_card')
                                ->label('Twitter Card Type')
                                ->options([
                                    'summary' => 'Summary',
                                    'summary_large_image' => 'Summary Large Image',
                                ])
                    ->default('summary_large_image'),

                            TextInput::make('seo.twitter_title')
                                ->label('Twitter Title')
                                ->maxLength(70)
                                ->columnSpanFull(),

                            Textarea::make('seo.twitter_description')
                                ->label('Twitter Description')
                                ->maxLength(200)
                                ->rows(3)
                                ->columnSpanFull(),

                            TextInput::make('seo.twitter_image_url')
                                ->label('Twitter Image URL')
                                ->url()->nullable()
                                ->helperText('Recommended: 1200x628px. Or choose from the media library below.')
                                ->columnSpanFull(),

                            MediaPicker::forField('seo.twitter_image_url', 'seo/twitter-images')
                    ->label('Twitter Image'),
                        ]),

                    // Advanced Tab
                    Tab::make('Advanced')
                        ->icon('heroicon-o-code-bracket')
                        ->schema([
                            Textarea::make('seo.schema_json')
                                ->label('Schema.org JSON-LD')
                                ->rows(10)
                                ->helperText('Structured data for rich search results. Must be valid JSON-LD.')
                                ->placeholder('{"@context":"https://schema.org","@type":"Organization",...}')
                                ->columnSpanFull(),

                            Textarea::make('seo.custom_head_scripts')
                                ->label('Custom Head Scripts')
                                ->rows(6)
                                ->helperText('Scripts to add in <head> tag. E.g., Google Analytics, Facebook Pixel.')
                                ->placeholder('<script>...</script>')
                                ->columnSpanFull(),

                            Textarea::make('seo.custom_body_scripts')
                                ->label('Custom Body Scripts')
                                ->rows(6)
                                ->helperText('Scripts to add before </body> tag.')
                                ->placeholder('<script>...</script>')
                                ->columnSpanFull(),
                        ]),
                ])
                ->columnSpanFull(),
        ];
    }
}