<?php

namespace App\Filament\Pages;

use App\Settings\WhyMaverickSettings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Filament\Notifications\Notification;

class ManageWhyMaverick extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'Homepage';
    protected static ?string $navigationLabel = 'Why Maverick';
    protected static ?int $navigationSort = 9;
    protected static string $settings = WhyMaverickSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Section Heading')
                ->description('These fields are required')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('heading_line1')
                            ->label('Heading Line 1')
                            ->maxLength(100),
                        TextInput::make('heading_line2')
                            ->label('Line 2 (Red/Accent)')
                            ->maxLength(100),
                    ]),
                    Textarea::make('subtitle')
                        ->rows(2)
                        ->maxLength(300)
                        ->columnSpanFull(),
                ]),

            Section::make('Tile 1: International Qualifications (Large)')
                ->schema([
                    TextInput::make('tile1_title')
                        ->maxLength(80),
                    RichEditor::make('tile1_desc')
                        ->maxLength(300)
                        ->helperText('Optional')
                        ->columnSpanFull(),
                ])->collapsible(),

            Section::make('Tile 2: Global University Network')
                ->schema([
                    TextInput::make('tile2_title')
                        ->maxLength(80),
                    RichEditor::make('tile2_desc')
                        ->maxLength(300)
                        ->helperText('Optional')
                        ->columnSpanFull(),
                ])->collapsible(),

            Section::make('Tile 3: Flexible Learning')
                ->schema([
                    TextInput::make('tile3_title')
                        ->maxLength(80),
                    RichEditor::make('tile3_desc')
                        ->maxLength(300)
                        ->helperText('Optional')
                        ->columnSpanFull(),
                ])->collapsible(),

            Section::make('Tile 4: Career Advancement')
                ->schema([
                    TextInput::make('tile4_title')
                        ->maxLength(80),
                    RichEditor::make('tile4_desc')
                        ->maxLength(300)
                        ->helperText('Optional')
                        ->columnSpanFull(),
                ])->collapsible(),

            Section::make('Tile 5: Industry Engagement')
                ->schema([
                    TextInput::make('tile5_title')
                        ->maxLength(80),
                    RichEditor::make('tile5_desc')
                        ->maxLength(300)
                        ->helperText('Optional')
                        ->columnSpanFull(),
                ])->collapsible(),

            Section::make('Tile 6: Academic Excellence')
                ->schema([
                    TextInput::make('tile6_title')
                        ->maxLength(80),
                    RichEditor::make('tile6_desc')
                        ->maxLength(300)
                        ->helperText('Optional')
                        ->columnSpanFull(),
                ])->collapsible(),
        ]);
    }

    /**
     * Show success notification after save
     */
    public function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('✅ Why Maverick section updated')
            ->body('All changes saved successfully.');
    }
}