<?php

namespace App\Filament\Pages;

use App\Settings\HeroSettings;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use App\Services\CloudinaryService;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ManageHero extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationGroup = 'Homepage';
    protected static ?string $navigationLabel = 'Hero Section';
    protected static ?int $navigationSort = 1;
    protected static string $settings = HeroSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Headline')
                ->schema([
                    TextInput::make('eyebrow')
                        ->label('Eyebrow Text')
                        ->required(),
                    Grid::make(3)->schema([
                        TextInput::make('headline_line1')->required(),
                        TextInput::make('headline_line2')->required(),
                        TextInput::make('headline_line3')
                            ->label('Headline Line 3 (Accent/Red)')
                            ->required(),
                    ]),
                    TextInput::make('subheading')->required()->columnSpanFull(),
                ]),

            Section::make('CTA Buttons')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('cta_primary_text')->label('Primary Button Text'),
                        TextInput::make('cta_primary_url')->label('Primary Button URL'),
                        TextInput::make('cta_secondary_text')->label('Secondary Button Text'),
                        TextInput::make('cta_secondary_url')->label('Secondary Button URL'),
                    ]),
                ]),

            Section::make('Background Video')
                ->schema([
                    TextInput::make('video_url')
                        ->label('Cloudinary Video URL')
                        ->url()
                        ->helperText('WebM format recommended.')
                        ->columnSpanFull(),
                ]),
        ]);
    }
}