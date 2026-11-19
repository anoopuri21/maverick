<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Settings\HomepagePopupSettings;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageHomepagePopup extends SettingsPage
{
    use HandlesCloudinaryImageFields;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Homepage';

    protected static ?string $navigationLabel = 'Homepage Popup';

    protected static ?int $navigationSort = 40;

    protected static string $settings = HomepagePopupSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Homepage popup')->schema([
                Toggle::make('enabled')
                    ->label('Show popup on homepage load')
                    ->helperText('Appears only when a background image is set.')
                    ->columnSpanFull(),
                MediaPicker::forField('image_url', 'homepage/popup')
                    ->label('Background image')
                    ->columnSpanFull(),
                TextInput::make('title')->label('Title')->columnSpanFull(),
                TextInput::make('heading')->label('Heading')->columnSpanFull(),
                TextInput::make('subheading')->label('Sub-heading')->columnSpanFull(),
                RichEditor::make('body')->label('Paragraph')->columnSpanFull(),
            ]),
        ]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = MediaPicker::syncFieldFromAsset($data, 'image_url');

        return $this->preserveExistingImageFields($data, app(static::getSettings()));
    }
}
