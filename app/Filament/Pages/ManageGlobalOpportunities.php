<?php

namespace App\Filament\Pages;

use App\Settings\GlobalOpportunitiesSettings;
use Filament\Forms\Components\Grid;
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
    protected static ?int $navigationSort = 10;
    protected static string $settings = GlobalOpportunitiesSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Section Heading')->schema([
                TextInput::make('heading')->required()->columnSpanFull(),
                Textarea::make('subtitle')->rows(2)->columnSpanFull(),
            ]),

            Section::make('Left Column: Global Opportunities')->schema([
                TextInput::make('left_title')->required()->columnSpanFull(),

                Section::make('Item 1')->schema([
                    Grid::make(3)->schema([
                        TextInput::make('opp1_title')->columnSpan(1),
                        TextInput::make('opp1_url')->label('URL')->columnSpan(2),
                    ]),
                    Textarea::make('opp1_desc')->label('Description')->rows(2),
                ])->collapsible(),

                Section::make('Item 2')->schema([
                    Grid::make(3)->schema([
                        TextInput::make('opp2_title')->columnSpan(1),
                        TextInput::make('opp2_url')->label('URL')->columnSpan(2),
                    ]),
                    Textarea::make('opp2_desc')->label('Description')->rows(2),
                ])->collapsible(),

                Section::make('Item 3')->schema([
                    Grid::make(3)->schema([
                        TextInput::make('opp3_title')->columnSpan(1),
                        TextInput::make('opp3_url')->label('URL')->columnSpan(2),
                    ]),
                    Textarea::make('opp3_desc')->label('Description')->rows(2),
                ])->collapsible(),

                Section::make('Item 4')->schema([
                    Grid::make(3)->schema([
                        TextInput::make('opp4_title')->columnSpan(1),
                        TextInput::make('opp4_url')->label('URL')->columnSpan(2),
                    ]),
                    Textarea::make('opp4_desc')->label('Description')->rows(2),
                ])->collapsible(),
            ]),

            Section::make('Right Column: Global Pathways')->schema([
                TextInput::make('right_title')->required()->columnSpanFull(),

                Section::make('Item 1')->schema([
                    Grid::make(3)->schema([
                        TextInput::make('path1_title')->columnSpan(1),
                        TextInput::make('path1_url')->label('URL')->columnSpan(2),
                    ]),
                    Textarea::make('path1_desc')->label('Description')->rows(2),
                ])->collapsible(),

                Section::make('Item 2')->schema([
                    Grid::make(3)->schema([
                        TextInput::make('path2_title')->columnSpan(1),
                        TextInput::make('path2_url')->label('URL')->columnSpan(2),
                    ]),
                    Textarea::make('path2_desc')->label('Description')->rows(2),
                ])->collapsible(),

                Section::make('Item 3')->schema([
                    Grid::make(3)->schema([
                        TextInput::make('path3_title')->columnSpan(1),
                        TextInput::make('path3_url')->label('URL')->columnSpan(2),
                    ]),
                    Textarea::make('path3_desc')->label('Description')->rows(2),
                ])->collapsible(),

                Section::make('Item 4')->schema([
                    Grid::make(3)->schema([
                        TextInput::make('path4_title')->columnSpan(1),
                        TextInput::make('path4_url')->label('URL')->columnSpan(2),
                    ]),
                    Textarea::make('path4_desc')->label('Description')->rows(2),
                ])->collapsible(),
            ]),
        ]);
    }
}