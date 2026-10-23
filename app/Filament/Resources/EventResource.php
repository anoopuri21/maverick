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
                Forms\Components\TextInput::make('event_type')
                    ->label('Event Type')
                    ->placeholder('e.g. Masterclass, Webinar, Workshop, Conference')
                    ->nullable(),
                Forms\Components\DatePicker::make('event_date')
                    ->required(),
                Forms\Components\TimePicker::make('event_start_time')
                    ->label('Start Time')
                    ->placeholder('e.g. 10:00 AM')
                    ->nullable(),
                Forms\Components\TimePicker::make('event_end_time')
                    ->label('End Time')
                    ->placeholder('e.g. 02:00 PM')
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
                    ->helperText('Photos from the event. Upload single or multiple images.'),
                Forms\Components\Repeater::make('gallery_videos')
                    ->label('Video Gallery')
                    ->schema([
                        Forms\Components\TextInput::make('url')
                            ->label('Video URL or Storage Path')
                            ->placeholder('e.g. https://www.youtube.com/watch?v=... or https://vimeo.com/... or uploaded video path')
                            ->required(),
                        Forms\Components\TextInput::make('title')
                            ->label('Video Title / Subtitle')
                            ->placeholder('e.g. Keynote Highlights')
                            ->nullable(),
                    ])
                    ->columnSpanFull()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? $state['url'] ?? null)
                    ->helperText('Add videos for the event video gallery. Supports YouTube, Vimeo, or direct MP4 URLs. Plays directly full-frame on the page without popups.'),
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
                Tables\Columns\TextColumn::make('event_type')
                    ->label('Type')
                    ->badge(),
                Tables\Columns\TextColumn::make('event_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('formatted_time')
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
