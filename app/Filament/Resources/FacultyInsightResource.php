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

    protected static ?string $navigationGroup = 'Insights';

    protected static ?string $navigationLabel = 'Faculty Voice';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Faculty Voice')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Insight')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (Set $set, $state) {
                                        $set('slug', Str::slug($state));
                                    }),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                Forms\Components\TextInput::make('badge')
                                    ->helperText('Topic label, e.g. Leadership, Strategy, Global Careers.'),
                                Forms\Components\Textarea::make('excerpt')
                                    ->rows(3)
                                    ->maxLength(400)
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('pull_quote')
                                    ->rows(2)
                                    ->helperText('One cinematic line that captures the voice.')
                                    ->columnSpanFull(),
                                Forms\Components\RichEditor::make('content')
                                    ->columnSpanFull()
                                    ->fileAttachmentsDirectory('faculty-voice-content'),
                                MediaPicker::forField('hero_image_url', 'faculty-insights/hero')
                                    ->label('Detail Hero Image')
                                    ->helperText('Background image on the Faculty Voice detail page. Falls back to the featured image if empty.')
                                    ->columnSpanFull(),
                                MediaPicker::forField('image_url', 'faculty-insights')
                                    ->label('Featured Image')
                                    ->helperText('Card image and the cinematic featured photo on the detail page.')
                                    ->columnSpanFull(),
                            ]),
                        Forms\Components\Tabs\Tab::make('Faculty')
                            ->schema([
                                Forms\Components\TextInput::make('faculty_name')
                                    ->label('Name'),
                                Forms\Components\TextInput::make('faculty_role')
                                    ->label('Role / Title'),
                                Forms\Components\Textarea::make('faculty_bio')
                                    ->label('Short Bio')
                                    ->rows(3)
                                    ->columnSpanFull(),
                                MediaPicker::forField('faculty_avatar_url', 'faculty-insights/avatars')
                                    ->label('Portrait')
                                    ->columnSpanFull(),
                            ]),
                        Forms\Components\Tabs\Tab::make('Publishing')
                            ->schema([
                                Forms\Components\DateTimePicker::make('published_at')
                                    ->default(now()),
                                Forms\Components\TextInput::make('reading_time_minutes')
                                    ->numeric()
                                    ->minValue(1)
                                    ->suffix('minutes')
                                    ->helperText('Leave empty to auto-calculate from the article body.'),
                                Forms\Components\TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0),
                                Forms\Components\Toggle::make('is_active')
                                    ->default(true),
                                Forms\Components\TextInput::make('link_url')
                                    ->url()
                                    ->helperText('Optional external override. Used only when this voice has no article body or excerpt.'),
                            ]),
                        Forms\Components\Tabs\Tab::make('SEO')
                            ->schema([
                                Forms\Components\TextInput::make('meta_title')
                                    ->maxLength(60),
                                Forms\Components\Textarea::make('meta_description')
                                    ->rows(3)
                                    ->maxLength(160),
                                MediaPicker::forField('og_image_url', 'faculty-insights/seo')
                                    ->label('OG Image')
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->limit(50),
                Tables\Columns\TextColumn::make('faculty_name')->label('Faculty')->toggleable(),
                Tables\Columns\TextColumn::make('badge'),
                ImageColumn::make('image_url')->size(60),
                Tables\Columns\TextColumn::make('published_at')->date('d M Y')->sortable(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
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
