<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Resources\OurStoryAwardResource\Pages;
use App\Models\OurStoryAward;
use App\Services\CloudinaryService;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class OurStoryAwardResource extends Resource
{
    use HandlesCloudinaryImageFields;

    protected static ?string $model = OurStoryAward::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $navigationGroup = 'Our Story Content';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('image_url')
                    ->label('Award Image')
                    ->image()
                    ->imageEditor()
                    ->required()
                    ->maxSize(5120)
                    ->fetchFileInformation(false)
                    ->getUploadedFileUsing(fn (?string $file): ?array => static::existingCloudinaryImage($file))
                    ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                        return app(CloudinaryService::class)
                            ->uploadImage($file->getRealPath(), 'our-story/awards');
                    }),
                Forms\Components\TextInput::make('title')
                    ->label('Award Title')
                    ->required()
                    ->maxLength(255),
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
