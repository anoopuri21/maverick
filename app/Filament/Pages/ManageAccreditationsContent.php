<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Settings\AccreditationsPageSettings;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageAccreditationsContent extends SettingsPage
{
    use HandlesCloudinaryImageFields;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'About Section';
    protected static ?string $navigationLabel = 'Accreditations Page Content';
    protected static ?int $navigationSort = 5;
    protected static string $settings = AccreditationsPageSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Hero Section')->schema([
                TextInput::make('hero_tag')->label('Eyebrow Tag'),
                TextInput::make('hero_heading_line1')->label('Heading Line 1'),
                TextInput::make('hero_heading_italic')->label('Heading (Italic)'),
                RichEditor::make('hero_description')->columnSpanFull(),
                MediaPicker::forField('hero_background_image', 'accreditations/hero')
                    ->label('Background Image')
                    ->columnSpanFull(),
            ]),

            Section::make('Credentials Section')->schema([
                TextInput::make('credentials_label')->label('Section Label'),
                TextInput::make('credentials_heading')->label('Heading'),
                TextInput::make('credentials_heading_span')->label('Heading Highlight'),
                Textarea::make('credentials_subtitle')->rows(3)->columnSpanFull(),
            ]),

            Section::make('Awards Section')->schema([
                TextInput::make('awards_label')->label('Section Label'),
                TextInput::make('awards_heading')->label('Heading'),
                TextInput::make('awards_heading_span')->label('Heading Highlight'),
                Textarea::make('awards_subtitle')->rows(3)->columnSpanFull(),
            ]),
        ]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = MediaPicker::syncFieldFromAsset($data, 'hero_background_image');

        return $this->preserveExistingImageFields($data, app(static::getSettings()));
    }
}
