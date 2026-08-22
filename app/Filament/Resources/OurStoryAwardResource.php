<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Resources\OurStoryAwardResource\Pages;
use App\Models\OurStoryAward;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Forms\Components\MediaPicker;

class OurStoryAwardResource extends Resource
{
    use HandlesCloudinaryImageFields;

    protected static ?string $model = OurStoryAward::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $navigationGroup = 'About Section';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Awards';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                MediaPicker::forField('image_url', 'our-story/awards')
                    ->label('Award Image')
                    ,
                Forms\Components\TextInput::make('title')
                    ->label('Award Title')
                    ->maxLength(255),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()->nullable()
                    ->default(0),
                Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_url')
                    ->label('Image')
                    ->size(80),
                TextColumn::make('title')
                    ->searchable()
                    ->limit(30),
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOurStoryAwards::route('/'),
            'create' => Pages\CreateOurStoryAward::route('/create'),
            'edit' => Pages\EditOurStoryAward::route('/{record}/edit'),
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
