<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GlobalAccessPointCountryResource\Pages;
use App\Models\GlobalAccessPointCountry;
use App\Support\IsoCountries;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rules\Unique;

class GlobalAccessPointCountryResource extends Resource
{
    protected static ?string $model = GlobalAccessPointCountry::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationGroup = 'Homepage';

    protected static ?string $navigationLabel = 'Access Point Countries';

    protected static ?int $navigationSort = 12;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('iso_numeric')
                    ->label('Country')
                    ->options(fn () => IsoCountries::options())
                    ->searchable()
                    ->required()
                    ->live()
                    ->helperText('Type a country name. The globe pin is set automatically.')
                    ->unique(
                        ignoreRecord: true,
                        modifyRuleUsing: fn (Unique $rule) => $rule->whereNull('deleted_at'),
                    )
                    ->afterStateUpdated(function (?string $state, Set $set): void {
                        if (! filled($state)) {
                            return;
                        }

                        $country = IsoCountries::find($state);
                        if (! $country) {
                            return;
                        }

                        $set('iso2', $country['iso2']);
                        $set('name', $country['name']);
                    }),
                Forms\Components\TextInput::make('name')
                    ->label('Display Name')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Shown in the country list. Defaults to the catalog name.'),
                Forms\Components\Hidden::make('iso2'),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->nullable()
                    ->default(0),
                Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('flag')
                    ->label('Flag')
                    ->getStateUsing(fn (GlobalAccessPointCountry $record): string => 'https://flagcdn.com/w40/'.strtolower($record->iso2).'.png')
                    ->height(20)
                    ->width(28),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('iso2')
                    ->label('Code')
                    ->badge(),
                TextColumn::make('sort_order')
                    ->sortable(),
                ToggleColumn::make('is_active'),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only')
                    ->placeholder('All'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGlobalAccessPointCountries::route('/'),
            'create' => Pages\CreateGlobalAccessPointCountry::route('/create'),
            'edit' => Pages\EditGlobalAccessPointCountry::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
