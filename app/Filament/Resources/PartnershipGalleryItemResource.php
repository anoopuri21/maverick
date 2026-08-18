<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\PartnershipGalleryItemResource\Pages;
use App\Models\PartnershipGalleryItem;
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

class PartnershipGalleryItemResource extends Resource
{
    use HandlesCloudinaryImageFields;

    protected static ?string $model = PartnershipGalleryItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Global Partners Page';
    protected static ?string $navigationLabel = 'Partnership Gallery';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            MediaPicker::forField('image_url', 'global-partners/gallery')
                ->label('Photo')
                ->required(),
            Forms\Components\Select::make('category')
                ->options(PartnershipGalleryItem::CATEGORIES)
                ->required()
                ->searchable(),
            Forms\Components\TextInput::make('badge')
                ->required()
                ->maxLength(100),
            Forms\Components\DatePicker::make('event_date')
                ->label('Event Date'),
            Forms\Components\TextInput::make('title')
                ->maxLength(255),
            Forms\Components\Textarea::make('caption')
                ->rows(2)
                ->columnSpanFull(),
            Forms\Components\Select::make('size')
                ->options(PartnershipGalleryItem::SIZES)
                ->default('medium')
                ->required(),
            Forms\Components\TextInput::make('sort_order')
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
                ImageColumn::make('image_url')->label('Photo')->size(80),
                TextColumn::make('badge')->searchable(),
                TextColumn::make('category')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => PartnershipGalleryItem::CATEGORIES[$state] ?? $state),
                TextColumn::make('event_date')->date('d M Y'),
                TextColumn::make('size')->badge(),
                TextColumn::make('sort_order')->sortable(),
                IconColumn::make('is_active')->boolean(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options(PartnershipGalleryItem::CATEGORIES),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPartnershipGalleryItems::route('/'),
            'create' => Pages\CreatePartnershipGalleryItem::route('/create'),
            'edit' => Pages\EditPartnershipGalleryItem::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
