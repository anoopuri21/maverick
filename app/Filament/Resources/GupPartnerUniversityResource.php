<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\GupPartnerUniversityResource\Pages;
use App\Models\GupPartnerUniversity;
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

class GupPartnerUniversityResource extends Resource
{
    use HandlesCloudinaryImageFields;

    protected static ?string $model = GupPartnerUniversity::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationGroup = 'About Section';
    protected static ?string $navigationLabel = 'Partner Universities';
    protected static ?int $navigationSort = 8;

        public static function shouldRegisterNavigation(): bool
    {
        // Managed from the consolidated About Section page tabs.
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('University Details')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('name')->required()->maxLength(255),
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->helperText('URL-safe identifier for this card.'),
                    Forms\Components\TextInput::make('abbreviation')
                        ->maxLength(12)
                        ->helperText('Logo fallback initials, e.g. GAU'),
                    Forms\Components\TextInput::make('country')->required(),
                    Forms\Components\TextInput::make('flag_emoji')
                        ->label('Flag Emoji')
                        ->maxLength(8)
                        ->helperText('e.g. 🇬🇧'),
                ]),
                Forms\Components\Textarea::make('recognition')
                    ->rows(3)
                    ->columnSpanFull(),
                MediaPicker::forField('logo_url', 'global-partners/universities')
                    ->label('Logo')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('cta_url')
                    ->label('CTA URL')
                    ->url()
                    ->helperText('Defaults to /programs when empty.')
                    ->columnSpanFull(),
            ]),
            Forms\Components\Section::make('Display')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                    Forms\Components\Toggle::make('is_active')->default(true),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo_url')->label('Logo')->size(50),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('country'),
                TextColumn::make('sort_order')->sortable(),
                IconColumn::make('is_active')->boolean(),
            ])
            ->defaultSort('sort_order')
            ->filters([
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
            'index' => Pages\ListGupPartnerUniversities::route('/'),
            'create' => Pages\CreateGupPartnerUniversity::route('/create'),
            'edit' => Pages\EditGupPartnerUniversity::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
