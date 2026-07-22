<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UniversityPartnerResource\Pages;
use App\Filament\Resources\UniversityPartnerResource\RelationManagers;
use App\Models\UniversityPartner;
use App\Filament\Concerns\HandlesCloudinaryImageFields;
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

class UniversityPartnerResource extends Resource
{
    use HandlesCloudinaryImageFields;

    protected static ?string $model = UniversityPartner::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Section::make('Basic Info')
                    ->schema([
                        \Filament\Forms\Components\Grid::make(2)->schema([
                            \Filament\Forms\Components\TextInput::make('name')
                                ->required(),
                            \Filament\Forms\Components\TextInput::make('country')
                                ->required(),
                            \Filament\Forms\Components\TextInput::make('country_code')
                                ->maxLength(3)
                                ->helperText('e.g. UAE, UK, USA'),
                            \Filament\Forms\Components\TextInput::make('city'),
                        ]),
                    ]),

                \Filament\Forms\Components\Section::make('Location (for Map)')
                    ->schema([
                        \Filament\Forms\Components\Grid::make(3)->schema([
                            \Filament\Forms\Components\TextInput::make('latitude')
                                ->numeric()
                                ->helperText('e.g. 25.2048'),
                            \Filament\Forms\Components\TextInput::make('longitude')
                                ->numeric()
                                ->helperText('e.g. 55.2708'),
                            \Filament\Forms\Components\Toggle::make('is_hub')
                                ->label('Main Hub')
                                ->helperText('Highlight this as hub'),
                        ]),
                    ]),

                \Filament\Forms\Components\Section::make('Details')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('recognition')
                            ->helperText('e.g. AACSB Accredited, QAA Reviewed')
                            ->columnSpanFull(),
                        FileUpload::make('logo_url')
                            ->label('Logo')
                            ->image()
                            ->maxSize(2048)
                            ->nullable()
                            ->getUploadedFileUsing(fn (?string $file): ?array => static::existingCloudinaryImage($file))
                            ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                                return app(CloudinaryService::class)
                                    ->uploadImage($file->getRealPath(), 'university-partners');
                            })
                            ->columnSpanFull(),
                        \Filament\Forms\Components\TextInput::make('website_url')->url(),
                        \Filament\Forms\Components\Textarea::make('description')->columnSpanFull(),
                    ]),

                \Filament\Forms\Components\Section::make('Programs Offered')
                    ->schema([
                        \Filament\Forms\Components\Repeater::make('programs')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('name')
                                    ->label('Program Name')
                                    ->required(),
                                \Filament\Forms\Components\TextInput::make('url')
                                    ->label('Program URL')
                                    ->required(),
                            ])
                            ->columns(2)
                            ->addActionLabel('Add Program')
                            ->columnSpanFull(),
                    ]),

                \Filament\Forms\Components\Section::make('Display')
                    ->schema([
                        \Filament\Forms\Components\Grid::make(2)->schema([
                            \Filament\Forms\Components\TextInput::make('sort_order')
                                ->numeric()
                                ->default(0),
                            \Filament\Forms\Components\Toggle::make('is_active')
                                ->default(true),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('country'),
                ImageColumn::make('logo_url')->size(50),
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
            'index' => Pages\ListUniversityPartners::route('/'),
            'create' => Pages\CreateUniversityPartner::route('/create'),
            'edit' => Pages\EditUniversityPartner::route('/{record}/edit'),
        ];
    }
}
