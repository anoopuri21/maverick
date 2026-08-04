<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Resources\MediaGalleryEventResource\Pages;
use App\Models\MediaGalleryEvent;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
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

class MediaGalleryEventResource extends Resource
{
    use HandlesCloudinaryImageFields;

    protected static ?string $model = MediaGalleryEvent::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Media Gallery';
    protected static ?string $navigationLabel = 'Recent Events';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255),
                DatePicker::make('event_date')
                    ->label('Event Date')
                    ->nullable(),
                TextInput::make('location')
                    ->label('Location')
                    ->maxLength(255)
                    ->nullable(),
                MediaPicker::forField('image_url', 'media-gallery/events')
                    ->label('Event Image')
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
                ImageColumn::make('image_url')
                    ->label('Image')
                    ->size(80),
                TextColumn::make('title')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('event_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('location')
                    ->searchable()
                    ->limit(20),
                TextColumn::make('sort_order')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->defaultSort('event_date', 'desc')
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
            'index' => Pages\ListMediaGalleryEvents::route('/'),
            'create' => Pages\CreateMediaGalleryEvent::route('/create'),
            'edit' => Pages\EditMediaGalleryEvent::route('/{record}/edit'),
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
