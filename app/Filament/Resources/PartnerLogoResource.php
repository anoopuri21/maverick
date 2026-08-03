<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnerLogoResource\Pages;
use App\Filament\Resources\PartnerLogoResource\RelationManagers;
use App\Models\PartnerLogo;
use App\Filament\Concerns\HandlesCloudinaryImageFields;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Forms\Components\MediaPicker;

class PartnerLogoResource extends Resource
{
    use HandlesCloudinaryImageFields;

    protected static ?string $model = PartnerLogo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Partner Logos';
    protected static ?string $pluralLabel = 'Partner Logos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Partner Logo')
                    ->tabs([
                        // Tab 1: Basic Info
                        Forms\Components\Tabs\Tab::make('Basic Info')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->helperText('Organization or institution name'),
                                
                                MediaPicker::forField('logo_url', 'partner-logos'),
                                
                                Forms\Components\Select::make('type')
                                    ->label('Category')
                                    ->options([
                                        'alumni' => 'Alumni Network',
                                        'accreditation' => 'Accreditation',
                                        'recognition' => 'Recognition',
                                        'award' => 'Award',
                                    ])
                                    ->required()
                                    ->searchable()
                                    ->helperText('Select the category this logo belongs to'),
                                
                                Forms\Components\TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Lower numbers appear first'),
                                
                                Forms\Components\Toggle::make('is_active')
                                    ->default(true)
                                    ->label('Active'),
                            ]),

                        // Tab 2: Display Info
                        Forms\Components\Tabs\Tab::make('Display Info')
                            ->icon('heroicon-o-eye')
                            ->schema([
                                Forms\Components\Placeholder::make('display_info')
                                    ->label('')
                                    ->content('This logo will be displayed on the following pages based on its category:')
                                    ->columnSpanFull(),
                                
                                Forms\Components\Placeholder::make('alumni_info')
                                    ->label('Alumni Category')
                                    ->content('→ Homepage Alumni Slider, Our Story Page'),
                                
                                Forms\Components\Placeholder::make('accreditation_info')
                                    ->label('Accreditation Category')
                                    ->content('→ Homepage Accreditations, Accreditation Page (Partnerships Section)'),
                                
                                Forms\Components\Placeholder::make('recognition_info')
                                    ->label('Recognition Category')
                                    ->content('→ Homepage Accreditations, Accreditation Page (Partnerships Section)'),
                                
                                Forms\Components\Placeholder::make('award_info')
                                    ->label('Award Category')
                                    ->content('→ Accreditation Page (Awards & Recognition Section)'),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                
                ImageColumn::make('logo_url')
                    ->size(50)
                    ->label('Logo'),
                
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'alumni' => 'info',
                        'accreditation' => 'success',
                        'recognition' => 'warning',
                        'award' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'alumni' => '🎓 Alumni',
                        'accreditation' => '✅ Accreditation',
                        'recognition' => '🏆 Recognition',
                        'award' => '🥇 Award',
                        default => $state,
                    })
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable()
                    ->label('Order'),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'alumni' => 'Alumni Network',
                        'accreditation' => 'Accreditation',
                        'recognition' => 'Recognition',
                        'award' => 'Award',
                    ])
                    ->label('Filter by Category'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartnerLogos::route('/'),
            'create' => Pages\CreatePartnerLogo::route('/create'),
            'edit' => Pages\EditPartnerLogo::route('/{record}/edit'),
        ];
    }
}
