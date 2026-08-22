<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Settings\PathwayProgramsSettings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManagePathwayPrograms extends SettingsPage
{
    use HandlesCloudinaryImageFields;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Global Pathways';
    protected static ?string $navigationLabel = 'Pathway Programs Page';
    protected static ?int $navigationSort = 2;
    protected static string $settings = PathwayProgramsSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Hero Section')->schema([
                Grid::make(2)->schema([
                    TextInput::make('tag')->label('Eyebrow / Tag')->columnSpanFull(),
                ]),
                Grid::make(2)->schema([
                    TextInput::make('heading')->label('Heading'),
                    TextInput::make('heading_italic')->label('Heading (Italic)'),
                ]),
                RichEditor::make('description')->label('Sub Heading')->columnSpanFull(),
                Grid::make(2)->schema([
                    MediaPicker::forField('background_image', 'pathway-programs')
                    ->label('Background Image (Media Library)'),
                    TextInput::make('background_image_url_input')->label('Or Image URL')
                        ->helperText('Used only if no media-library image is uploaded.'),
                ]),
            ]),

            Section::make('Overview Section')->schema([
                TextInput::make('overview_label')->label('Label')->columnSpanFull(),
                Grid::make(2)->schema([
                    TextInput::make('overview_heading')->label('Heading'),
                    TextInput::make('overview_heading_italic')->label('Heading (Italic)'),
                ]),
                RichEditor::make('overview_body')->label('Body')->columnSpanFull(),
            ]),

            Section::make('Pathways List')->schema([
                TextInput::make('pathways_label')->label('Section Label'),
                Grid::make(2)->schema([
                    TextInput::make('pathways_heading')->label('Heading'),
                    TextInput::make('pathways_heading_italic')->label('Heading (Italic)'),
                ]),
                TextInput::make('pathways_cta_label')->label('Pathway CTA Button Label'),
                Textarea::make('pathways_empty_message')->label('Empty State Message')->rows(2),
            ]),
        ]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = MediaPicker::syncFieldFromAsset($data, 'background_image');

        // Image URL manual fallback (only if no media-library image chosen).
        if (empty($data['background_image']) && ! empty($data['background_image_url_input'])) {
            $data['background_image'] = $data['background_image_url_input'];
        }

        // Drop the transient manual-URL field so it is not persisted to settings.
        unset($data['background_image_url_input']);

        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['background_image_url_input'] = $data['background_image'] ?? null;
        return $data;
    }
}
