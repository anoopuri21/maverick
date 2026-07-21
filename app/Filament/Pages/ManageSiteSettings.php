<?php

namespace App\Filament\Pages;

use App\Settings\SiteSettings;
use App\Services\CloudinaryService;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ManageSiteSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Site Settings';
    protected static ?string $navigationLabel = 'General Settings';
    protected static ?int $navigationSort = 1;
    protected static string $settings = SiteSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Logos')
                ->schema([
                    Grid::make(2)->schema([
                        FileUpload::make('logo_url')
                            ->label('Logo (Dark)')
                            ->image()
                            ->maxSize(2048)
                            ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                                return app(CloudinaryService::class)
                                    ->uploadImage($file->getRealPath(), 'site');
                            }),
                        FileUpload::make('logo_white_url')
                            ->label('Logo (White)')
                            ->image()
                            ->maxSize(2048)
                            ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                                return app(CloudinaryService::class)
                                    ->uploadImage($file->getRealPath(), 'site');
                            }),
                    ]),
                ]),

            Section::make('Contact Info')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('phone')->required(),
                        TextInput::make('whatsapp_number')
                            ->label('WhatsApp Number (with country code, no +)')
                            ->helperText('Example: 971501441670')
                            ->required(),
                        TextInput::make('email')->email()->required(),
                        TextInput::make('apply_now_url')
                            ->label('Apply Now Button URL')
                            ->required(),
                    ]),
                    TextInput::make('address')->required()->columnSpanFull(),
                ]),

            Section::make('Social Links')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('facebook_url')->url(),
                        TextInput::make('instagram_url')->url(),
                        TextInput::make('linkedin_url')->url(),
                        TextInput::make('youtube_url')->url(),
                    ]),
                ]),
        ]);
    }
}