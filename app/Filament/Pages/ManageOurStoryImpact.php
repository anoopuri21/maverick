<?php

namespace App\Filament\Pages;

use App\Settings\OurStoryImpactSettings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageOurStoryImpact extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Our Story Page';
    protected static ?string $navigationLabel = 'Our Impact';
    protected static ?int $navigationSort = 4;

    protected static bool $shouldRegisterNavigation = false;
    protected static string $settings = OurStoryImpactSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Section Header')
                ->schema([
                    TextInput::make('heading')
                        ->label('Heading')
                        ->required(),
                    RichEditor::make('description')
                        ->label('Description')
                        ->toolbarButtons([
                            'bold',
                            'italic',
                            'underline',
                            'link',
                            'bulletList',
                            'orderedList',
                            'redo',
                            'undo',
                        ])
                        ->columnSpanFull(),
                ]),

            Section::make('Stats')
                ->columns(2)
                ->schema([
                    TextInput::make('stat_1_value')->label('Stat 1 Value'),
                    TextInput::make('stat_1_label')->label('Stat 1 Label'),
                    TextInput::make('stat_2_value')->label('Stat 2 Value'),
                    TextInput::make('stat_2_label')->label('Stat 2 Label'),
                    TextInput::make('stat_3_value')->label('Stat 3 Value'),
                    TextInput::make('stat_3_label')->label('Stat 3 Label'),
                    TextInput::make('stat_4_value')->label('Stat 4 Value'),
                    TextInput::make('stat_4_label')->label('Stat 4 Label'),
                ]),
        ]);
    }
}
