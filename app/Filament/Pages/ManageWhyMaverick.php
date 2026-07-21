<?php

namespace App\Filament\Pages;

use App\Settings\WhyMaverickSettings;
use App\Filament\Concerns\HasStrictValidation;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Filament\Notifications\Notification;

class ManageWhyMaverick extends SettingsPage
{
    use HasStrictValidation;

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
                            ->required()
                            ->maxLength(100),
                        TextInput::make('heading_line2')
                            ->label('Line 2 (Red/Accent)')
                            ->required()
                            ->maxLength(100),
                    ]),
                    Textarea::make('subtitle')
                        ->required()
                        ->rows(2)
                        ->maxLength(300)
                        ->columnSpanFull(),
                ]),

            Section::make('Tile 1: International Qualifications (Large)')
                ->schema([
                    TextInput::make('tile1_title')
                        ->required()
                        ->maxLength(80),
                    Textarea::make('tile1_desc')
                        ->rows(2)
                        ->maxLength(300)
                        ->helperText('Optional')
                        ->columnSpanFull(),
                ])->collapsible(),

            Section::make('Tile 2: Global University Network')
                ->schema([
                    TextInput::make('tile2_title')
                        ->required()
                        ->maxLength(80),
                    Textarea::make('tile2_desc')
                        ->rows(2)
                        ->maxLength(300)
                        ->helperText('Optional')
                        ->columnSpanFull(),
                ])->collapsible(),

            Section::make('Tile 3: Flexible Learning')
                ->schema([
                    TextInput::make('tile3_title')
                        ->required()
                        ->maxLength(80),
                    Textarea::make('tile3_desc')
                        ->rows(2)
                        ->maxLength(300)
                        ->helperText('Optional')
                        ->columnSpanFull(),
                ])->collapsible(),

            Section::make('Tile 4: Career Advancement')
                ->schema([
                    TextInput::make('tile4_title')
                        ->required()
                        ->maxLength(80),
                    Textarea::make('tile4_desc')
                        ->rows(2)
                        ->maxLength(300)
                        ->helperText('Optional')
                        ->columnSpanFull(),
                ])->collapsible(),

            Section::make('Tile 5: Industry Engagement')
                ->schema([
                    TextInput::make('tile5_title')
                        ->required()
                        ->maxLength(80),
                    Textarea::make('tile5_desc')
                        ->rows(2)
                        ->maxLength(300)
                        ->helperText('Optional')
                        ->columnSpanFull(),
                ])->collapsible(),

            Section::make('Tile 6: Academic Excellence')
                ->schema([
                    TextInput::make('tile6_title')
                        ->required()
                        ->maxLength(80),
                    Textarea::make('tile6_desc')
                        ->rows(2)
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