<?php

namespace App\Filament\Pages;

use App\Settings\WhatIsMaverickSettings;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageWhatIsMaverick extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';
    protected static ?string $navigationGroup = 'Homepage';
    protected static ?string $navigationLabel = 'What Is Maverick';
    protected static ?int $navigationSort = 5;
    protected static string $settings = WhatIsMaverickSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Heading')
                ->schema([
                    TextInput::make('heading')
                        ->required()
                        ->columnSpanFull(),
                ]),

            Section::make('Impact Statements')
                ->columns(1)
                ->schema([
                    TextInput::make('statement1')->label('Statement 1')->required(),
                    TextInput::make('statement2')->label('Statement 2')->required(),
                    TextInput::make('statement3')->label('Statement 3')->required(),
                    TextInput::make('statement4')->label('Statement 4')->required(),
                    TextInput::make('statement5')->label('Statement 5')->required(),
                ]),

            Section::make('Final Text')
                ->schema([
                    TextInput::make('final_text')
                        ->label('Final Accent Text')
                        ->required()
                        ->columnSpanFull(),
                ]),
        ]);
    }
}