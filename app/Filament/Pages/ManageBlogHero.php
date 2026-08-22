<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Settings\BlogHeroSettings;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageBlogHero extends SettingsPage
{
    use HandlesCloudinaryImageFields;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'Insights';
    protected static ?string $navigationLabel = 'Blog Page Hero';
    protected static ?int $navigationSort = 7;
    protected static string $settings = BlogHeroSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Blog Listing Hero')->schema([
                TextInput::make('eyebrow')->label('Eyebrow Tag'),
                TextInput::make('heading')->label('Heading')->columnSpanFull(),
                RichEditor::make('description')->columnSpanFull(),
                MediaPicker::forField('image_url', 'blog/hero')
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
