<?php

namespace App\Filament\Pages;

use App\Settings\DeiMatrixSettings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageDeiMatrix extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';

    protected static ?string $navigationGroup = 'Homepage';

    protected static ?string $navigationLabel = 'DEI Matrix';

    protected static ?int $navigationSort = 12;

    protected static string $settings = DeiMatrixSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Section Header')->schema([
                TextInput::make('label')->label('Section Label'),
                TextInput::make('heading')->columnSpanFull(),
                RichEditor::make('description')->columnSpanFull(),
            ]),
            Section::make('Row D')->schema([
                Grid::make(2)->schema([
                    TextInput::make('row_d_letter')->label('Letter')->maxLength(4),
                    TextInput::make('row_d_heading')->label('Heading'),
                ]),
                RichEditor::make('row_d_definition')->label('Definition')->columnSpanFull(),
                TextInput::make('row_d_practice')->label('Practice')->columnSpanFull(),
            ]),
            Section::make('Row E')->schema([
                Grid::make(2)->schema([
                    TextInput::make('row_e_letter')->label('Letter')->maxLength(4),
                    TextInput::make('row_e_heading')->label('Heading'),
                ]),
                RichEditor::make('row_e_definition')->label('Definition')->columnSpanFull(),
                TextInput::make('row_e_practice')->label('Practice')->columnSpanFull(),
            ]),
            Section::make('Row I')->schema([
                Grid::make(2)->schema([
                    TextInput::make('row_i_letter')->label('Letter')->maxLength(4),
                    TextInput::make('row_i_heading')->label('Heading'),
                ]),
                RichEditor::make('row_i_definition')->label('Definition')->columnSpanFull(),
                TextInput::make('row_i_practice')->label('Practice')->columnSpanFull(),
            ]),
        ]);
    }
}
