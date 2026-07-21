<?php

namespace App\Filament\Pages;

use App\Settings\NumbersSettings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageNumbers extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Homepage';
    protected static ?string $navigationLabel = 'Numbers Section';
    protected static ?int $navigationSort = 2;
    protected static string $settings = NumbersSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Section Heading')
                ->schema([
                    Grid::make(3)->schema([
                        TextInput::make('heading_line1')->required(),
                        TextInput::make('heading_line2')->required(),
                        TextInput::make('heading_line3')
                            ->label('Line 3 (Red/Accent)')
                            ->required(),
                    ]),
                    TextInput::make('context_text')
                        ->label('Context Paragraph')
                        ->columnSpanFull(),
                    Grid::make(2)->schema([
                        TextInput::make('context_link_text')
                            ->label('Link Text'),
                        TextInput::make('context_link_url')
                            ->label('Link URL'),
                    ]),
                ]),

            Section::make('Statistics')
                ->columns(2)
                ->schema([
                    TextInput::make('stat1_value')->label('Stat 1 Value'),
                    TextInput::make('stat1_label')->label('Stat 1 Label'),
                    TextInput::make('stat2_value')->label('Stat 2 Value'),
                    TextInput::make('stat2_label')->label('Stat 2 Label'),
                    TextInput::make('stat3_value')->label('Stat 3 Value'),
                    TextInput::make('stat3_label')->label('Stat 3 Label'),
                    TextInput::make('stat4_value')->label('Stat 4 Value'),
                    TextInput::make('stat4_label')->label('Stat 4 Label'),
                    TextInput::make('stat5_value')->label('Stat 5 Value'),
                    TextInput::make('stat5_label')->label('Stat 5 Label'),
                    TextInput::make('stat6_value')->label('Stat 6 Value'),
                    TextInput::make('stat6_label')->label('Stat 6 Label'),
                ]),
        ]);
    }
}