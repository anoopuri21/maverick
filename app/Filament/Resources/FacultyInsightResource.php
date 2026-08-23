<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\FacultyInsightResource\Pages;
use App\Models\FacultyInsight;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class FacultyInsightResource extends Resource
{
    use HandlesCloudinaryImageFields;

    protected static ?string $model = FacultyInsight::class;

    protected static ?string $navigationIcon = 'heroicon-o-microphone';

    protected static ?string $navigationGroup = 'Homepage';

    protected static ?string $navigationLabel = 'Faculty Insights';

    protected static ?int $navigationSort = 11;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Title (Name)')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                $set('slug', Str::slug($state ?? ''));
                            }),
                        Forms\Components\TextInput::make('faculty_role')
                            ->label('Designation/Position'),
                        Forms\Components\TextInput::make('country')
                            ->label('Country'),
                        Forms\Components\Textarea::make('content')
                            ->label('Description')
                            ->rows(6)
                            ->columnSpanFull(),
                        MediaPicker::forField('image_url', 'faculty-insights')
                            ->label('Featured Image')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('slug')
                            ->hidden(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->limit(50),
                Tables\Columns\TextColumn::make('faculty_role')->label('Designation')->toggleable(),
                Tables\Columns\TextColumn::make('country')->toggleable(),
                ImageColumn::make('image_url')->label('Image')->size(60),
                Tables\Columns\TextInputColumn::make('sort_order')->label('Order')->sortable(),
                Tables\Columns\ToggleColumn::make('is_active')->label('Active'),
            ])
            ->defaultSort('sort_order')
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
        return [];
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
