<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Resources\MediaGalleryPhotoResource\Pages;
use App\Models\MediaGalleryPhoto;
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

class MediaGalleryPhotoResource extends Resource
{
    use HandlesCloudinaryImageFields;

    protected static ?string $model = MediaGalleryPhoto::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'About Section';
    protected static ?string $navigationLabel = 'Gallery Photos';
    protected static ?int $navigationSort = 12;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                MediaPicker::forField('image_url', 'media-gallery/photos')
                    ->label('Photo')
                    ->required(),
                TextInput::make('caption')
                    ->label('Caption')
                    ->maxLength(255)
                    ->nullable(),
                Select::make('category')
                    ->label('Category')
                    ->options([
                        'Events' => 'Events',
                        'Campus' => 'Campus',
                        'Students' => 'Students',
                        'Graduations' => 'Graduations',
                        'Media Coverage' => 'Media Coverage',
                    ])
                    ->searchable()
                    ->nullable(),
                Select::make('size')
                    ->label('Collage Size')
                    ->options([
                        'small' => 'Small',
                        'medium' => 'Medium',
                        'large' => 'Large',
                    ])
                    ->default('medium')
                    ->helperText('Controls the item footprint in the masonry collage.'),
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
                    ->label('Photo')
                    ->size(80),
                TextColumn::make('caption')
                    ->searchable()
                    ->limit(30),
                TextColumn::make('category')
                    ->badge()
                    ->searchable(),
                TextColumn::make('size')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'large' => 'warning',
                        'small' => 'info',
                        default => 'gray',
                    }),
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
            'index' => Pages\ListMediaGalleryPhotos::route('/'),
            'create' => Pages\CreateMediaGalleryPhoto::route('/create'),
            'edit' => Pages\EditMediaGalleryPhoto::route('/{record}/edit'),
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
