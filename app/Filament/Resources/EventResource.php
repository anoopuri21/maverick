<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EventResource extends Resource
{
    use HandlesCloudinaryImageFields;

    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Insights';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Event Management')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Event Details')
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
                                    ->label('Event Date')
                                    ->nullable(),
                                Forms\Components\TimePicker::make('event_start_time')
                                    ->label('Start Time')
                                    ->displayFormat('h:i A')
                                    ->placeholder('e.g. 10:00 AM')
                                    ->nullable(),
                                Forms\Components\TimePicker::make('event_end_time')
                                    ->label('End Time')
                                    ->displayFormat('h:i A')
                                    ->placeholder('e.g. 02:00 PM')
                                    ->nullable(),
                                Forms\Components\TextInput::make('location')
                                    ->placeholder('e.g., Grand Mercure Hotel, Dubai / Online Zoom'),
                                Forms\Components\TextInput::make('link_url')
                                    ->url()
                                    ->nullable()
                                    ->placeholder('External link or custom registration URL'),
                                Forms\Components\RichEditor::make('description')
                                    ->columnSpanFull(),
                                Forms\Components\Toggle::make('is_active')
                                    ->default(true),
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make('Media Gallery')
                            ->schema([
                                MediaPicker::forField('display_image', 'events/display')
                                    ->label('Main Display Image')
                                    ->helperText('Main cover photo for event cards and detail hero banner.'),
                                Forms\Components\Repeater::make('gallery_images')
                                    ->label('Event Photo Gallery')
                                    ->afterStateHydrated(function (Forms\Components\Repeater $component, $state) {
                                        if (is_array($state)) {
                                            $formatted = array_map(function ($item) {
                                                if (is_string($item)) {
                                                    return ['url' => $item];
                                                }
                                                return $item;
                                            }, $state);
                                            $component->state($formatted);
                                        }
                                    })
                                    ->schema([
                                        MediaPicker::forField('url', 'events/gallery')
                                            ->label('Photo')
                                            ->helperText('Select from Media Library or upload new photo.'),
                                    ])
                                    ->itemLabel(fn (array $state): ?string => ! empty($state['url']) ? basename($state['url']) : 'Photo')
                                    ->collapsible()
                                    ->reorderable()
                                    ->helperText('Add single or multiple photos for the event gallery.'),
                                Forms\Components\Repeater::make('gallery_videos')
                                    ->label('Video Gallery')
                                    ->afterStateHydrated(function (Forms\Components\Repeater $component, $state) {
                                        if (is_array($state)) {
                                            $formatted = array_map(function ($item) {
                                                if (is_string($item)) {
                                                    return ['url' => $item, 'title' => ''];
                                                }
                                                return $item;
                                            }, $state);
                                            $component->state($formatted);
                                        }
                                    })
                                    ->schema([
                                        Forms\Components\TextInput::make('url')
                                            ->label('Video URL')
                                            ->placeholder('e.g. https://www.youtube.com/watch?v=... or Vimeo URL')
                                            ->nullable(),
                                        Forms\Components\TextInput::make('title')
                                            ->label('Video Title')
                                            ->placeholder('e.g. Executive Summary')
                                            ->nullable(),
                                    ])
                                    ->columnSpanFull()
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? $state['url'] ?? null),
                            ]),

                        Forms\Components\Tabs\Tab::make('Registration Form Fields')
                            ->schema([
                                Forms\Components\Placeholder::make('info')
                                    ->label('Custom Registration Fields')
                                    ->content('Standard mandatory fields (Full Name, Email, Phone Number, Guest Count, and Joining Confirmation) are automatically included. Add any additional custom fields for this event below.'),
                                Forms\Components\Repeater::make('registration_fields')
                                    ->label('Dynamic Form Fields')
                                    ->schema([
                                        Forms\Components\TextInput::make('label')
                                            ->label('Field Label / Question')
                                            ->required()
                                            ->placeholder('e.g., Organization / Company Name'),
                                        Forms\Components\Select::make('type')
                                            ->label('Field Type')
                                            ->options([
                                                'text' => 'Short Text (Input)',
                                                'textarea' => 'Long Text (Textarea)',
                                                'select' => 'Dropdown Select',
                                                'radio' => 'Multiple Choice (Radio)',
                                                'checkbox' => 'Checkbox (Multiple Options)',
                                            ])
                                            ->default('text')
                                            ->required()
                                            ->live(),
                                        Forms\Components\TagsInput::make('options')
                                            ->label('Options (for Dropdown, Radio, or Checkbox)')
                                            ->placeholder('Add option and press Enter')
                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['select', 'radio', 'checkbox'])),
                                        Forms\Components\Toggle::make('is_required')
                                            ->label('Required Field')
                                            ->default(false),
                                    ])
                                    ->columns(2)
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? 'Custom Field'),
                            ]),
                    ])
                    ->columnSpanFull(),
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
