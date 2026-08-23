<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\OurStoryTestimonialResource\Pages;
use App\Models\OurStoryTestimonial;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OurStoryTestimonialResource extends Resource
{
    protected static ?string $model = OurStoryTestimonial::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'About Section';

    protected static ?string $navigationLabel = 'Testimonials';

    protected static ?int $navigationSort = 4;

    public static function shouldRegisterNavigation(): bool
    {
        // Managed from the Our Story Page tabs (ManageOurStory).
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->maxLength(255),
                TextInput::make('organisation')
                    ->maxLength(255),
                TextInput::make('position')
                    ->label('Position / Designation')
                    ->maxLength(255),
                TextInput::make('country')
                    ->maxLength(255),
                Select::make('rating')
                    ->options([
                        1 => '1',
                        2 => '2',
                        3 => '3',
                        4 => '4',
                        5 => '5',
                    ])
                    ->default(5)
                    ,
                RichEditor::make('testimonial')
                    ->columnSpanFull(),
                MediaPicker::make('media_asset_id')
                    ->folder('our-story/testimonials')
                    ->urlField('photo'),
                TextInput::make('sort_order')
                    ->numeric()->nullable()
                    ->default(0),
                Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort_order')
                    ->sortable(),
                ImageColumn::make('photo')
                    ->label('Photo')
                    ->circular()
                    ->size(48),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('position')
                    ->label('Position')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('organisation')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('country')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('rating')
                    ->formatStateUsing(function ($state): string {
                        $filled = (int) $state;
                        $empty = max(0, 5 - $filled);

                        return str_repeat('★', $filled).str_repeat('☆', $empty);
                    }),
                ToggleColumn::make('is_active'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only')
                    ->placeholder('All'),
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
            'index' => Pages\ListOurStoryTestimonials::route('/'),
            'create' => Pages\CreateOurStoryTestimonial::route('/create'),
            'edit' => Pages\EditOurStoryTestimonial::route('/{record}/edit'),
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
