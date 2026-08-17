<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccreditationAwardResource\Pages;
use App\Filament\Resources\PartnerLogoResource;
use App\Models\PartnerLogo;
use App\Filament\Concerns\HandlesCloudinaryImageFields;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Forms\Components\MediaPicker;

class AccreditationAwardResource extends Resource
{
    use HandlesCloudinaryImageFields;

    protected static ?string $model = PartnerLogo::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $navigationGroup = 'Accreditations Page';
    protected static ?string $navigationLabel = 'Awards & Recognition';
    protected static ?string $pluralLabel = 'Awards & Recognition';
    protected static ?int $navigationSort = 3;

    /**
     * Only manage logos of type "award" here.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('type', 'award');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Award or organisation name'),

                MediaPicker::forField('logo_url', 'partner-logos')
                    ->nullable(),

                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->maxLength(500)
                    ->helperText('Short description for this award'),

                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->helperText('Lower numbers appear first'),

                Forms\Components\Toggle::make('is_active')
                    ->default(true)
                    ->label('Active'),
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

                Tables\Columns\TextColumn::make('description')
                    ->limit(40)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable()
                    ->label('Order'),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status'),
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
            'index' => Pages\ListAccreditationAwards::route('/'),
            'create' => Pages\CreateAccreditationAward::route('/create'),
            'edit' => Pages\EditAccreditationAward::route('/{record}/edit'),
        ];
    }
}
