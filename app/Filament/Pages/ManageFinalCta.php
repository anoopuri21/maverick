<?php

namespace App\Filament\Pages;

use App\Settings\FinalCtaSettings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageFinalCta extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationGroup = 'Homepage';
    protected static ?string $navigationLabel = 'Final CTA';
    protected static ?int $navigationSort = 6;
    protected static string $settings = FinalCtaSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Content')
                ->schema([
                    TextInput::make('heading')->required()->columnSpanFull(),
                    TextInput::make('subtitle')->columnSpanFull(),
                ]),

            Section::make('Buttons')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('btn_primary_text')->label('Primary Button Text'),
                        TextInput::make('btn_primary_url')->label('Primary Button URL'),
                        TextInput::make('btn_secondary_text')->label('Secondary Button Text'),
                        TextInput::make('btn_secondary_url')->label('Secondary Button URL'),
                    ]),
                ]),

            Section::make('Phone')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('phone_text')->label('Phone Label Text'),
                        TextInput::make('phone_number')->label('Phone Number'),
                    ]),
                ]),
        ]);
    }
}