<?php

namespace App\Filament\Pages;

use App\Settings\WhatWeDoSettings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageWhatWeDo extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationGroup = 'Homepage';
    protected static ?string $navigationLabel = 'What We Do';
    protected static ?int $navigationSort = 7;
    protected static string $settings = WhatWeDoSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Section Heading')->schema([
                Grid::make(2)->schema([
                    TextInput::make('heading_line1')->required(),
                    TextInput::make('heading_line2')->label('Line 2 (Red/Accent)')->required(),
                ]),
                Textarea::make('context_text')->rows(2)->columnSpanFull(),
            ]),

            Section::make('Pillar 1: Academic Qualifications')->schema([
                TextInput::make('pillar1_title')->required(),
                Textarea::make('pillar1_desc')->rows(2)->columnSpanFull(),
                Grid::make(3)->schema([
                    TextInput::make('pillar1_item1')->label('Item 1'),
                    TextInput::make('pillar1_item2')->label('Item 2'),
                    TextInput::make('pillar1_item3')->label('Item 3'),
                ]),
                Grid::make(2)->schema([
                    TextInput::make('pillar1_cta_text')->label('CTA Text'),
                    TextInput::make('pillar1_cta_url')->label('CTA URL'),
                ]),
            ]),

            Section::make('Pillar 2: Professional Development')->schema([
                TextInput::make('pillar2_title')->required(),
                Textarea::make('pillar2_desc')->rows(2)->columnSpanFull(),
                Grid::make(3)->schema([
                    TextInput::make('pillar2_item1')->label('Item 1'),
                    TextInput::make('pillar2_item2')->label('Item 2'),
                    TextInput::make('pillar2_item3')->label('Item 3'),
                ]),
                Grid::make(2)->schema([
                    TextInput::make('pillar2_cta_text')->label('CTA Text'),
                    TextInput::make('pillar2_cta_url')->label('CTA URL'),
                ]),
            ]),

            Section::make('Pillar 3: International Opportunities')->schema([
                TextInput::make('pillar3_title')->required(),
                Textarea::make('pillar3_desc')->rows(2)->columnSpanFull(),
                Grid::make(2)->schema([
                    TextInput::make('pillar3_item1')->label('Item 1'),
                    TextInput::make('pillar3_item2')->label('Item 2'),
                    TextInput::make('pillar3_item3')->label('Item 3'),
                    TextInput::make('pillar3_item4')->label('Item 4'),
                ]),
                Grid::make(2)->schema([
                    TextInput::make('pillar3_cta_text')->label('CTA Text'),
                    TextInput::make('pillar3_cta_url')->label('CTA URL'),
                ]),
            ]),
        ]);
    }
}