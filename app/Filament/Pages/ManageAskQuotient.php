<?php

namespace App\Filament\Pages;

use App\Settings\AskQuotientSettings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageAskQuotient extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Homepage';

    protected static ?string $navigationLabel = 'ASK Quotient';

    protected static ?int $navigationSort = 13;

    protected static string $settings = AskQuotientSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Section Header')->schema([
                TextInput::make('label')->label('Section Label'),
                TextInput::make('heading')->columnSpanFull(),
                RichEditor::make('description')->columnSpanFull(),
            ]),
            Section::make('Card A')->schema([
                Grid::make(2)->schema([
                    TextInput::make('card_a_letter')->label('Letter')->maxLength(4),
                    TextInput::make('card_a_heading')->label('Heading'),
                ]),
                TextInput::make('card_a_keywords')->label('Keywords')->helperText('Separate with ·')->columnSpanFull(),
                RichEditor::make('card_a_definition')->label('Definition')->columnSpanFull(),
            ]),
            Section::make('Card S')->schema([
                Grid::make(2)->schema([
                    TextInput::make('card_s_letter')->label('Letter')->maxLength(4),
                    TextInput::make('card_s_heading')->label('Heading'),
                ]),
                TextInput::make('card_s_keywords')->label('Keywords')->helperText('Separate with ·')->columnSpanFull(),
                RichEditor::make('card_s_definition')->label('Definition')->columnSpanFull(),
            ]),
            Section::make('Card K')->schema([
                Grid::make(2)->schema([
                    TextInput::make('card_k_letter')->label('Letter')->maxLength(4),
                    TextInput::make('card_k_heading')->label('Heading'),
                ]),
                TextInput::make('card_k_keywords')->label('Keywords')->helperText('Separate with ·')->columnSpanFull(),
                RichEditor::make('card_k_definition')->label('Definition')->columnSpanFull(),
            ]),
        ]);
    }
}
