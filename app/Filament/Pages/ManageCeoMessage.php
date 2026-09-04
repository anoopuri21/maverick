<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Settings\CeoSettings;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ManageCeoMessage extends SettingsPage
{
    use HandlesCloudinaryImageFields;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Homepage';
    protected static ?string $navigationLabel = 'CEO Message';
    protected static ?int $navigationSort = 4;
    protected static string $settings = CeoSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Section Heading')
                ->schema([
                    TextInput::make('label')->label('Section Label'),
                    TextInput::make('heading_line1')->label('Heading Line 1'),
                    TextInput::make('heading_line2')->label('Heading Line 2 (Accent)'),
                ]),

            Section::make('CEO Details')
                ->schema([
                    TextInput::make('name')
                        ->label('CEO Name')
                        ,
                    TextInput::make('designation')
                        ->label('Designation')
                        ,
                    TextInput::make('badge_text')
                        ->label('Badge Text (on image)'),
                ]),

            Section::make('Message Content')
                ->schema([
                    RichEditor::make('quote')
                        ->label('Quote')
                        ->columnSpanFull(),
                    RichEditor::make('body_paragraph1')
                        ->label('Paragraph 1')
                        ->columnSpanFull(),
                    RichEditor::make('body_paragraph2')
                        ->label('Paragraph 2')
                        ->columnSpanFull(),
                ]),

            Section::make('CEO Photo')
                ->schema([
                    FileUpload::make('image_url')
                        ->label('CEO Photo')
                        ->image()
                        ->imageEditor()
                        ->maxSize(5120)
                        ->columnSpanFull()
                        ->fetchFileInformation(false)
                        ->getUploadedFileUsing(function (?string $file) {
                            return static::existingCloudinaryImage($file);
                        })
                    ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                            return cloudinary_upload($file->getRealPath() ?: null, 'homepage/ceo');
                        }),
                ]),
        ]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->preserveExistingImageFields($data, app(static::$settings));
    }
}