<?php

namespace App\Filament\Pages;

use App\Settings\CeoSettings;
use App\Services\CloudinaryService;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ManageCeoMessage extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Homepage';
    protected static ?string $navigationLabel = 'CEO Message';
    protected static ?int $navigationSort = 4;
    protected static string $settings = CeoSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([

            Section::make('CEO Details')
                ->schema([
                    TextInput::make('name')
                        ->label('CEO Name')
                        ->required(),
                    TextInput::make('designation')
                        ->label('Designation')
                        ->required(),
                    TextInput::make('badge_text')
                        ->label('Badge Text (on image)'),
                ]),

            Section::make('Message Content')
                ->schema([
                    Textarea::make('quote')
                        ->label('Quote')
                        ->rows(3)
                        ->required()
                        ->columnSpanFull(),
                    Textarea::make('body_paragraph1')
                        ->label('Paragraph 1')
                        ->rows(4)
                        ->columnSpanFull(),
                    Textarea::make('body_paragraph2')
                        ->label('Paragraph 2')
                        ->rows(4)
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
                        ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                            return app(CloudinaryService::class)
                                ->uploadImage($file->getRealPath(), 'homepage/ceo');
                        }),
                ]),
        ]);
    }
}