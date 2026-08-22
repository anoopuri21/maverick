<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Settings\GlobalOpportunitiesSettings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageGlobalOpportunities extends SettingsPage
{
    use HandlesCloudinaryImageFields;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationGroup = 'Global Pathways';
    protected static ?string $navigationLabel = 'Global Opportunities';
    protected static ?int $navigationSort = 1;
    protected static string $settings = GlobalOpportunitiesSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Section Heading')->schema([
                TextInput::make('heading')->columnSpanFull(),
                Textarea::make('subtitle')->rows(2)->columnSpanFull(),
            ]),

            Section::make('Global Opportunities Items')->schema([
                TextInput::make('left_title')->label('Column Title')->columnSpanFull(),
                Repeater::make('opportunities')
                    ->label('Opportunity Items')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title'),
                            TextInput::make('url')->label('URL'),
                        ]),
                        RichEditor::make('desc')->label('Description')->columnSpanFull(),
                        MediaPicker::forField('image', 'global-opportunities')
                    ->label('Image')
                    ->helperText('Upload from library (priority) or use the URL field below.')
                    ->columnSpanFull(),
                        TextInput::make('image_url')->label('Image URL (fallback)')
                            ->helperText('Used only if no image is uploaded.'),
                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
            ]),

            Section::make('Global Pathways Items')->schema([
                TextInput::make('right_title')->label('Column Title')->columnSpanFull(),
                Repeater::make('pathways')
                    ->label('Pathway Items')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title'),
                            TextInput::make('url')->label('URL'),
                        ]),
                        RichEditor::make('desc')->label('Description')->columnSpanFull(),
                        MediaPicker::forField('image', 'global-pathways')
                    ->label('Image')
                    ->helperText('Upload from library (priority) or use the URL field below.')
                    ->columnSpanFull(),
                        TextInput::make('image_url')->label('Image URL (fallback)')
                            ->helperText('Used only if no image is uploaded.'),
                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
            ]),
        ]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Sync MediaPicker asset id -> image for each item (image priority).
        foreach ($data['opportunities'] ?? [] as &$item) {
            $item = \App\Filament\Forms\Components\MediaPicker::syncFieldFromAsset($item, 'image');
        }
        unset($item);
        foreach ($data['pathways'] ?? [] as &$item) {
            $item = \App\Filament\Forms\Components\MediaPicker::syncFieldFromAsset($item, 'image');
        }
        unset($item);

        $data['opportunities'] = array_values($data['opportunities'] ?? []);
        $data['pathways'] = array_values($data['pathways'] ?? []);
        return $data;
    }
}
