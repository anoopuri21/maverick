<?php

namespace App\Filament\Pages;

use App\Settings\GlobalOpportunitiesSettings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageGlobalOpportunities extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationGroup = 'Homepage';
    protected static ?string $navigationLabel = 'Global Opportunities';
    protected static ?int $navigationSort = 12;
    protected static string $settings = GlobalOpportunitiesSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Section Heading')->schema([
                TextInput::make('heading')->required()->columnSpanFull(),
                Textarea::make('subtitle')->rows(2)->columnSpanFull(),
            ]),

            Section::make('Left Column: Global Opportunities')->schema([
                TextInput::make('left_title')->label('Column Title')->required()->columnSpanFull(),
                Repeater::make('opportunities')
                    ->label('Opportunity Items')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')->required(),
                            TextInput::make('url')->label('URL'),
                        ]),
                        Textarea::make('desc')->label('Description')->rows(2)->columnSpanFull(),
                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
            ]),

            Section::make('Right Column: Global Pathways')->schema([
                TextInput::make('right_title')->label('Column Title')->required()->columnSpanFull(),
                Repeater::make('pathways')
                    ->label('Pathway Items')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')->required(),
                            TextInput::make('url')->label('URL'),
                        ]),
                        Textarea::make('desc')->label('Description')->rows(2)->columnSpanFull(),
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
        $data['opportunities'] = array_values($data['opportunities'] ?? []);
        $data['pathways'] = array_values($data['pathways'] ?? []);
        return $data;
    }
}
