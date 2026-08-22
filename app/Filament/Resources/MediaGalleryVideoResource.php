<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Resources\MediaGalleryVideoResource\Pages;
use App\Models\MediaGalleryVideo;
use Filament\Forms;
use Filament\Forms\Components\Select;
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

class MediaGalleryVideoResource extends Resource
{
    use HandlesCloudinaryImageFields;

    protected static ?string $model = MediaGalleryVideo::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationGroup = 'About Section';
    protected static ?string $navigationLabel = 'Featured Videos';
    protected static ?int $navigationSort = 13;

        public static function shouldRegisterNavigation(): bool
    {
        // Managed from the consolidated About Section page tabs.
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Title')
                    ->maxLength(255),
                TextInput::make('video_url')
                    ->label('Video URL')
                    ->url()->nullable()
                    ->nullable()
                    ->placeholder('https://www.youtube.com/watch?v=...'),
                MediaPicker::forField('thumbnail_url', 'media-gallery/videos')
                    ->label('Thumbnail')
                    ->nullable(),
                TextInput::make('duration')
                    ->label('Duration')
                    ->placeholder('1:02')
                    ->maxLength(20)
                    ->nullable(),
                Select::make('category')
                    ->label('Category')
                    ->options([
                        'Highlights' => 'Highlights',
                        'Events' => 'Events',
                        'Campus' => 'Campus',
                        'Students' => 'Students',
                        'Graduations' => 'Graduations',
                    ])
                    ->searchable()
                    ->nullable(),
                TextInput::make('sort_order')
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
                ImageColumn::make('thumbnail_url')
                    ->label('Thumbnail')
                    ->size(80),
                TextColumn::make('title')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('duration'),
                TextColumn::make('category')
                    ->badge()
                    ->searchable(),
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
            'index' => Pages\ListMediaGalleryVideos::route('/'),
            'create' => Pages\CreateMediaGalleryVideo::route('/create'),
            'edit' => Pages\EditMediaGalleryVideo::route('/{record}/edit'),
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
