<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnerLogoResource\Pages;
use App\Filament\Resources\PartnerLogoResource\RelationManagers;
use App\Models\PartnerLogo;
use App\Services\CloudinaryService;
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

class PartnerLogoResource extends Resource
{
    protected static ?string $model = PartnerLogo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\FileUpload::make('logo_url')
                    ->image()
                    ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                        return app(CloudinaryService::class)->uploadImage($file->getRealPath(), 'partner-logos');
                    }),
                Forms\Components\Select::make('type')
                    ->options([
                        'alumni' => 'Alumni Network',
                        'accreditation' => 'Accreditation',
                    ])
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
                Tables\Columns\TextColumn::make('name'),
                ImageColumn::make('logo_url')->size(50),
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\TextColumn::make('sort_order'),
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
            'index' => Pages\ListPartnerLogos::route('/'),
            'create' => Pages\CreatePartnerLogo::route('/create'),
            'edit' => Pages\EditPartnerLogo::route('/{record}/edit'),
        ];
    }
}
