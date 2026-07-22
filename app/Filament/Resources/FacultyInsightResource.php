<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FacultyInsightResource\Pages;
use App\Filament\Resources\FacultyInsightResource\RelationManagers;
use App\Models\FacultyInsight;
use App\Services\CloudinaryService;
use App\Filament\Concerns\HandlesCloudinaryImageFields;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class FacultyInsightResource extends Resource
{
    use HandlesCloudinaryImageFields;
    protected static ?string $model = FacultyInsight::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Forms\Set $set, $state) {
                        $set('slug', \Str::slug($state));
                    }),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('badge'),
                Forms\Components\FileUpload::make('image_url')
                    ->image()
                    ->nullable()
                    ->getUploadedFileUsing(fn (?string $file): ?array => static::existingCloudinaryImage($file))
                    ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                        return app(CloudinaryService::class)->uploadImage($file->getRealPath(), 'faculty-insights');
                    }),
                Forms\Components\TextInput::make('link_url')
                    ->url(),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric(),
                Forms\Components\Toggle::make('is_active'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('badge'),
                ImageColumn::make('image_url')->size(60),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListFacultyInsights::route('/'),
            'create' => Pages\CreateFacultyInsight::route('/create'),
            'edit' => Pages\EditFacultyInsight::route('/{record}/edit'),
        ];
    }
}
