<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MediaAssetResource\Pages;
use App\Models\MediaAsset;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MediaAssetResource extends Resource
{
    protected static ?string $model = MediaAsset::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Site Settings';

    protected static ?string $navigationLabel = 'Media Library';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('upload')
                    ->label('Upload Image')
                    ->image()
                    ->imageEditor()
                    ->visibleOn('create')
                    ->maxSize(5120)
                    ->dehydrated(false),
                TextInput::make('folder')
                    ->label('Folder')
                    ->default('library')
                    ->maxLength(255)
                    ->visibleOn('create')
                    ->dehydrated(false),
                TextInput::make('original_name')
                    ->label('Name')
                    ->maxLength(255),
                TextInput::make('alt')
                    ->label('Alt text')
                    ->maxLength(255),
                TextInput::make('url')
                    ->label('URL')
                    ->disabled()
                    ->dehydrated(false)
                    ->visibleOn('edit'),
                TextInput::make('hash')
                    ->disabled()
                    ->dehydrated(false)
                    ->visibleOn('edit'),
                Forms\Components\Placeholder::make('folder_display')
                    ->label('Folder')
                    ->content(fn (?MediaAsset $record): string => $record?->folder ?? '—')
                    ->visibleOn('edit'),
                TextInput::make('disk_env')
                    ->label('Environment')
                    ->disabled()
                    ->dehydrated(false)
                    ->visibleOn('edit'),
                Forms\Components\Placeholder::make('used_display')
                    ->label('Used')
                    ->content(fn (?MediaAsset $record): string => $record?->used ? 'Yes' : 'No')
                    ->visibleOn('edit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('url')
                    ->label('Preview')
                    ->square()
                    ->size(64),
                TextColumn::make('original_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('used')
                    ->label('Used')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('folder')
                    ->badge()
                    ->searchable(),
                TextColumn::make('disk_env')
                    ->label('Env')
                    ->badge(),
                TextColumn::make('size_bytes')
                    ->label('Size')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state / 1024, 1).' KB' : '—'),
                TextColumn::make('hash')
                    ->limit(12)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->copyable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('used')
                    ->label('Used')
                    ->placeholder('All')
                    ->trueLabel('Used')
                    ->falseLabel('Unused'),
                Tables\Filters\TernaryFilter::make('is_duplicate')
                    ->label('Duplicate')
                    ->placeholder('All')
                    ->trueLabel('Duplicates')
                    ->falseLabel('Unique'),
                Tables\Filters\SelectFilter::make('folder')
                    ->options(fn () => MediaAsset::query()
                    ->whereNotNull('folder')
                    ->distinct()
                    ->pluck('folder', 'folder')
                    ->all()),
                Tables\Filters\SelectFilter::make('disk_env')
                    ->options(fn () => MediaAsset::query()
                    ->whereNotNull('disk_env')
                    ->distinct()
                    ->pluck('disk_env', 'disk_env')
                    ->all()),
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
            'index' => Pages\ListMediaAssets::route('/'),
            'create' => Pages\CreateMediaAsset::route('/create'),
            'edit' => Pages\EditMediaAsset::route('/{record}/edit'),
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
