<?php

namespace App\Filament\Pages;

use App\Settings\AccreditationCinematicSettings;
use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageAccreditationCinematic extends SettingsPage
{
    use HandlesCloudinaryImageFields;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';
    protected static ?string $navigationGroup = 'About Section';
    protected static ?string $navigationLabel = 'Cinematic Section';
    protected static ?int $navigationSort = 10;
    protected static string $settings = AccreditationCinematicSettings::class;

    public static function shouldRegisterNavigation(): bool
    {
        // Rendered inside ManageAccreditationPage ("Cinematic Section" tab).
        return false;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = MediaPicker::syncFieldFromAsset($data, 'image_url');
        $data = $this->preserveExistingImageFields($data, app(static::getSettings()));

        return $data;
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Section Content')
                ->schema([
                    TextInput::make('heading')
                        ->required()
                        ->columnSpanFull(),
                    RichEditor::make('text')
                        ->label('Description')
                        ->toolbarButtons([
                            'bold',
                            'italic',
                            'underline',
                            'link',
                            'redo',
                            'undo',
                        ])
                        ->columnSpanFull(),
                ]),

            Section::make('Background Media')
                ->schema([
                    MediaPicker::forField('image_url', 'accreditations/cinematic')
                        ->label('Background Image')
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
