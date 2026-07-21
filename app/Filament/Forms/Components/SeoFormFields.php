<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use App\Services\CloudinaryService;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class SeoFormFields
{
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
                                    ->url()
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

                            FileUpload::make('seo.og_image_url')
                                ->label('OG Image')
                                ->image()
                                ->imageEditor()
                                ->maxSize(5120)
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->helperText('Recommended: 1200x630px. Used for Facebook, LinkedIn shares.')
                                ->columnSpanFull()
                                ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                                    return app(CloudinaryService::class)
                                        ->uploadImage($file->getRealPath(), 'seo/og-images');
                                }),

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

                            FileUpload::make('seo.twitter_image_url')
                                ->label('Twitter Image')
                                ->image()
                                ->imageEditor()
                                ->maxSize(5120)
                                ->helperText('Recommended: 1200x628px')
                                ->columnSpanFull()
                                ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                                    return app(CloudinaryService::class)
                                        ->uploadImage($file->getRealPath(), 'seo/twitter-images');
                                }),
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