<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MediaPressMentionResource\Pages;
use App\Models\MediaPressMention;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MediaPressMentionResource extends Resource
{
    protected static ?string $model = MediaPressMention::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'Media Gallery';
    protected static ?string $navigationLabel = 'Media Coverage';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('publication')
                    ->label('Publication')
                    ->required()
                    ->maxLength(255),
                TextInput::make('code')
                    ->label('Logo Code')
                    ->maxLength(8)
                    ->placeholder('FT')
                    ->helperText('Short letters shown inside the logo tile (e.g. FT, TG, FOR).'),
                TextInput::make('title')
                    ->label('Article Title')
                    ->required()
                    ->maxLength(255),
                TextInput::make('url')
                    ->label('Article URL')
                    ->url()
                    ->nullable(),
                TextInput::make('publication_date')
                    ->label('Publication Date')
                    ->maxLength(60)
                    ->placeholder('14 Feb 2024')
                    ->nullable(),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('publication')
                    ->searchable(),
                TextColumn::make('code')
                    ->badge(),
                TextColumn::make('title')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('publication_date'),
                TextColumn::make('sort_order')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->defaultSort('sort_order')
            ->filters([
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMediaPressMentions::route('/'),
            'create' => Pages\CreateMediaPressMention::route('/create'),
            'edit' => Pages\EditMediaPressMention::route('/{record}/edit'),
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
