<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Settings\NewsHeroSettings;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageNewsHero extends SettingsPage
{
    use HandlesCloudinaryImageFields;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationGroup = 'Insights';
    protected static ?string $navigationLabel = 'News Page Hero';
    protected static ?int $navigationSort = 8;
    protected static string $settings = NewsHeroSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('News Listing Hero')->schema([
                TextInput::make('eyebrow')->label('Eyebrow Tag'),
                TextInput::make('heading')->label('Heading')->columnSpanFull(),
                RichEditor::make('description')->columnSpanFull(),
                MediaPicker::forField('image_url', 'news/hero')
                    ->label('Background Image')
                    ->columnSpanFull(),
            ]),
        ]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = MediaPicker::syncFieldFromAsset($data, 'image_url');

        return $this->preserveExistingImageFields($data, app(static::getSettings()));
    }
}
