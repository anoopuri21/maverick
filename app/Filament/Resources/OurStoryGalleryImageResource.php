<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Resources\OurStoryGalleryImageResource\Pages;
use App\Models\OurStoryGalleryImage;
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

class OurStoryGalleryImageResource extends Resource
{
    use HandlesCloudinaryImageFields;

    protected static ?string $model = OurStoryGalleryImage::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'About Section';
    protected static ?string $navigationLabel = 'Gallery Images';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                MediaPicker::forField('image_url', 'our-story/gallery')
                    ->label('Gallery Image')
                    ->required(),
                TextInput::make('caption')
                    ->label('Caption')
                    ->maxLength(255)
                    ->nullable(),
                Select::make('category')
                    ->label('Category')
                    ->options([
                        'Graduation Ceremony' => 'Graduation Ceremony',
                        'UK–Oman Digital Connectivity Forum' => 'UK–Oman Digital Connectivity Forum',
                        'University of Buckingham Collaboration' => 'University of Buckingham Collaboration',
                        'Masterclass Events' => 'Masterclass Events',
                        'Corporate Training Sessions' => 'Corporate Training Sessions',
                        'MOU Signing Ceremonies' => 'MOU Signing Ceremonies',
                        'Student Workshops' => 'Student Workshops',
                    ])
                    ->searchable()
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
                TextColumn::make('caption')
                    ->searchable()
                    ->limit(30),
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOurStoryGalleryImages::route('/'),
            'create' => Pages\CreateOurStoryGalleryImage::route('/create'),
            'edit' => Pages\EditOurStoryGalleryImage::route('/{record}/edit'),
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
