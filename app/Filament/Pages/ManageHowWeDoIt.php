<?php

namespace App\Filament\Pages;

use App\Settings\HowWeDoItSettings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageHowWeDoIt extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';
    protected static ?string $navigationGroup = 'Homepage';
    protected static ?string $navigationLabel = 'How We Do It';
    protected static ?int $navigationSort = 8;
    protected static string $settings = HowWeDoItSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Section Heading')->schema([
                Grid::make(2)->schema([
                    TextInput::make('heading_line1')->required(),
                    TextInput::make('heading_line2')->label('Line 2 (Red/Accent)')->required(),
                ]),
                Textarea::make('subtitle')->rows(2)->columnSpanFull(),
            ]),

            Section::make('Step 1: Learn')->schema([
                Grid::make(2)->schema([
                    TextInput::make('step1_title')->required(),
                    TextInput::make('step1_subtitle'),
                ]),
                Textarea::make('step1_desc')->rows(3)->columnSpanFull(),
            ]),

            Section::make('Step 2: Connect')->schema([
                Grid::make(2)->schema([
                    TextInput::make('step2_title')->required(),
                    TextInput::make('step2_subtitle'),
                ]),
                Textarea::make('step2_desc')->rows(3)->columnSpanFull(),
            ]),

            Section::make('Step 3: Transform')->schema([
                Grid::make(2)->schema([
                    TextInput::make('step3_title')->required(),
                    TextInput::make('step3_subtitle'),
                ]),
                Textarea::make('step3_desc')->rows(3)->columnSpanFull(),
            ]),
        ]);
    }
}