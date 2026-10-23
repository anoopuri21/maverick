<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Insights';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(Event::class, 'slug', ignoreRecord: true),
                Forms\Components\DatePicker::make('event_date')
                    ->required(),
                Forms\Components\TimePicker::make('event_time')
                    ->label('Event Time')
                    ->placeholder('e.g. 10:00 AM')
                    ->nullable(),
                Forms\Components\TextInput::make('location')
                    ->placeholder('e.g., London, UK / Online Zoom'),
                Forms\Components\TextInput::make('link_url')
                    ->url()
                    ->nullable()
                    ->placeholder('External link or registration URL'),
                Forms\Components\FileUpload::make('display_image')
                    ->image()
                    ->directory('events/display')
                    ->imageEditor()
                    ->helperText('Main display image for listing and hero banner.'),
                Forms\Components\FileUpload::make('gallery_images')
                    ->image()
                    ->multiple()
                    ->reorderable()
                    ->directory('events/gallery')
                    ->helperText('Photos from the event (especially for past events gallery section). Upload single or multiple images.'),
                Forms\Components\RichEditor::make('description')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('display_image')
                    ->square(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('event_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('event_time')
                    ->label('Time'),
                Tables\Columns\TextColumn::make('location')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
