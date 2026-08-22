<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Settings\MediaGalleryPageSettings;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageMediaGalleryContent extends SettingsPage
{
    use HandlesCloudinaryImageFields;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'About Section';
    protected static ?string $navigationLabel = 'Media Gallery Content';
    protected static ?int $navigationSort = 6;
    protected static string $settings = MediaGalleryPageSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Hero Section')->schema([
                TextInput::make('hero_tag')->label('Eyebrow Tag'),
                TextInput::make('hero_heading_line1')->label('Heading Line 1'),
                TextInput::make('hero_heading_italic')->label('Heading (Italic)'),
                RichEditor::make('hero_description')->columnSpanFull(),
                MediaPicker::forField('hero_background_image', 'media-gallery/hero')
                    ->label('Background Image')
                    ->columnSpanFull(),
            ]),

            Section::make('Photos Section')->schema([
                TextInput::make('photos_label')->label('Section Label'),
                TextInput::make('photos_heading')->label('Heading'),
                Textarea::make('photos_subheading')->rows(3)->columnSpanFull(),
            ]),

            Section::make('Videos Section')->schema([
                TextInput::make('videos_label')->label('Section Label'),
                TextInput::make('videos_heading')->label('Heading'),
                Textarea::make('videos_subheading')->rows(3)->columnSpanFull(),
            ]),
        ]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = MediaPicker::syncFieldFromAsset($data, 'hero_background_image');

        return $this->preserveExistingImageFields($data, app(static::getSettings()));
    }
}
