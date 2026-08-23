<?php

namespace App\Filament\Pages;

use App\Settings\SiteSettings;
use App\Services\CloudinaryService;
use App\Filament\Concerns\HandlesCloudinaryImageFields;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ManageSiteSettings extends SettingsPage
{
    use HandlesCloudinaryImageFields;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = $this->preserveExistingImageFields($data, app(static::getSettings()));

        return $data;
    }

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
                            ->fetchFileInformation(false)
                            ->nullable()
                            ->getUploadedFileUsing(fn (?string $file): ?array => static::existingCloudinaryImage($file))
                            ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                                return cloudinary_upload($file->getRealPath() ?: null, 'site');
                            }),
                        FileUpload::make('logo_white_url')
                            ->label('Logo (White)')
                            ->image()
                            ->maxSize(2048)
                            ->fetchFileInformation(false)
                            ->nullable()
                            ->getUploadedFileUsing(fn (?string $file): ?array => static::existingCloudinaryImage($file))
                            ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                                return cloudinary_upload($file->getRealPath() ?: null, 'site');
                            }),
                    ]),
                ]),

            Section::make('UAE Contact')
                ->schema([
                    TextInput::make('address')->label('UAE Address')->columnSpanFull(),
                    Grid::make(2)->schema([
                        TextInput::make('phone')->label('UAE Phone'),
                        TextInput::make('phone_secondary')->label('UAE Secondary Phone')->nullable(),
                        TextInput::make('email')->label('UAE Email')->email()->nullable(),
                        TextInput::make('whatsapp_number')
                            ->label('WhatsApp Number (with country code, no +)')
                            ->helperText('Example: 971501441670'),
                        TextInput::make('office_hours')->label('Office Hours')->nullable(),
                        TextInput::make('apply_now_url')
                            ->label('Apply Now Button URL'),
                    ]),
                ]),

            Section::make('UK Contact')
                ->schema([
                    TextInput::make('uk_address')->label('UK Address')->columnSpanFull(),
                    Grid::make(2)->schema([
                        TextInput::make('uk_phone')->label('UK Phone')->nullable(),
                        TextInput::make('uk_email')->label('UK Email')->email()->nullable(),
                    ]),
                ]),

            Section::make('Social Links')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('facebook_url')->url()->nullable(),
                        TextInput::make('instagram_url')->url()->nullable(),
                        TextInput::make('linkedin_url')->url()->nullable(),
                        TextInput::make('twitter_url')->label('Twitter / X URL')->url()->nullable(),
                        TextInput::make('youtube_url')->url()->nullable(),
                    ]),
                ]),
        ]);
    }
}