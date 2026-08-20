<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Settings\GlobalOpportunitiesPageSettings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageGlobalOpportunitiesPage extends SettingsPage
{
    use HandlesCloudinaryImageFields;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    // Individual top-level link.
    protected static ?string $navigationGroup = null;
    protected static ?string $navigationLabel = 'Global Opportunities Page';
    protected static ?int $navigationSort = 14;
    protected static string $settings = GlobalOpportunitiesPageSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Hero Section')->schema([
                Grid::make(2)->schema([
                    TextInput::make('tag')->label('Eyebrow Tag')->columnSpanFull(),
                    TextInput::make('heading')->label('Heading (Line 1)')->required(),
                    TextInput::make('heading_italic')->label('Heading (Italic)')->required(),
                ]),
                Textarea::make('description')->label('Sub Heading / Description')->rows(3)->columnSpanFull(),
                Grid::make(2)->schema([
                    MediaPicker::forField('background_image', 'global-opportunities-page')
                        ->label('Background Image (Media Library)')
                        ->helperText('Upload from library (priority) or use the URL field below.'),
                    TextInput::make('background_image_url_input')->label('Or Background Image URL')->url(),
                ]),
            ]),

            Section::make('Overview Section')->schema([
                TextInput::make('overview_label')->label('Label / Eyebrow')->columnSpanFull(),
                Grid::make(2)->schema([
                    TextInput::make('overview_heading')->label('Heading (Line 1)')->required(),
                    TextInput::make('overview_heading_italic')->label('Heading (Italic)')->required(),
                ]),
                Textarea::make('overview_body')->label('Body Content')
                    ->rows(5)
                    ->helperText('Write in a clear, human, SEO-friendly tone. Separate paragraphs with blank lines.')
                    ->columnSpanFull(),
            ]),

            Section::make('Cards Section Header')->schema([
                Grid::make(2)->schema([
                    TextInput::make('cards_label')->label('Label / Eyebrow'),
                    TextInput::make('cards_heading')->label('Heading (Line 1)')->required(),
                    TextInput::make('cards_heading_italic')->label('Heading (Italic)')->required(),
                ]),
            ]),
        ]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = \App\Filament\Forms\Components\MediaPicker::syncFieldFromAsset($data, 'background_image');

        if (empty($data['background_image']) && ! empty($data['background_image_url_input'])) {
            $data['background_image'] = $data['background_image_url_input'];
        }
        unset($data['background_image_url_input']);

        return $data;
    }
}
