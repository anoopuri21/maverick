<?php

namespace App\Filament\Pages;

use App\Settings\OurStoryCeoQuoteSettings;
use App\Services\CloudinaryService;
use App\Filament\Concerns\HandlesCloudinaryImageFields;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ManageOurStoryCeoQuote extends SettingsPage
{
    use HandlesCloudinaryImageFields;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = $this->preserveExistingImageFields($data, app(static::getSettings()));

        return $data;
    }

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Our Story Page';
    protected static ?string $navigationLabel = 'CEO Message';
    protected static ?int $navigationSort = 5;
    protected static string $settings = OurStoryCeoQuoteSettings::class;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form->schema([

            Section::make('CEO Details')
                ->schema([
                    TextInput::make('ceo_name')
                        ->label('CEO Name')
                        ,
                    TextInput::make('ceo_designation')
                        ->label('Designation')
                        ,
                ]),

            Section::make('Quote Content')
                ->schema([
                    RichEditor::make('quote')
                        ->label('Quote')
                        ->columnSpanFull(),
                ]),

            Section::make('CEO Photo')
                ->schema([
                    FileUpload::make('ceo_image_url')
                        ->label('CEO Photo')
                        ->image()
                        ->imageEditor()
                        ->maxSize(5120)
                        ->columnSpanFull()
                        ->fetchFileInformation(false)
                        ->nullable()
                        ->getUploadedFileUsing(fn (?string $file): ?array => static::existingCloudinaryImage($file))
                        ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                            return cloudinary_upload($file->getRealPath() ?: null, 'our-story/ceo');
                        }),
                ]),
        ]);
    }
}
