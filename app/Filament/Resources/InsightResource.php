<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Resources\InsightResource\Pages;
use App\Models\Insight;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Filament\Forms\Components\MediaPicker;

class InsightResource extends Resource
{
    use HandlesCloudinaryImageFields;

    protected static ?string $model = Insight::class;

    protected static ?string $navigationLabel = 'Insights';
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Content')->schema([
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        if (blank($get('slug'))) {
                            $set('slug', \Illuminate\Support\Str::slug($state));
                        }
                    }),

                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Textarea::make('excerpt')
                    ->maxLength(500)
                    ->rows(3)
                    ->helperText('Short summary (max 500 characters).'),

                RichEditor::make('content')
                    ->required()
                    ->columnSpanFull()
                    ->fileAttachmentsDirectory('insight-content-images'),
            ]),

            Section::make('Media')->schema([
                MediaPicker::forField('featured_image_url', 'blog-images')
                    ->label('Featured Image')
                    ->helperText('Optional — a branded cover will be shown automatically if left empty.'),

                TextInput::make('featured_image_alt')
                    ->label('Image Alt Text')
                    ->helperText('Used for SEO & accessibility.'),
            ]),

            Section::make('Category')->schema([
                CheckboxList::make('categories')
                    ->options([
                        'blogs' => 'Blogs',
                        'news'  => 'News',
                    ])
                    ->required()
                    ->columns(2)
                    ->helperText('Select one or both. Selecting both will display this item on both the Blogs and News pages.'),
            ]),

            Section::make('Organization')->schema([
                TagsInput::make('tags')
                    ->helperText('Press enter after each tag.'),

                Toggle::make('is_featured')
                    ->helperText('Only one item per selected category can be featured at a time — enabling this will automatically un-feature the current featured item in each selected category.'),
            ]),

            Section::make('Author')->schema([
                TextInput::make('author_name')
                    ->default('Maverick Business Academy')
                    ->required(),

                TextInput::make('author_avatar_url')
                    ->label('Author Avatar URL')
                    ->url(),

                Textarea::make('author_bio')
                    ->maxLength(500)
                    ->rows(2),
            ]),

            Section::make('Publishing')->schema([
                DateTimePicker::make('published_at')
                    ->default(now())
                    ->required(),

                TextInput::make('reading_time_minutes')
                    ->numeric()
                    ->minValue(1)
                    ->suffix('minutes')
                    ->helperText('Auto-calculated or override manually.'),
            ]),

            Section::make('SEO')->schema([
                TextInput::make('meta_title')
                    ->maxLength(255),

                Textarea::make('meta_description')
                    ->maxLength(500)
                    ->rows(2),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('featured_image_url')
                    ->label('Image')
                    ->square(),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                TextColumn::make('categories')
                    ->badge()
                    ->formatStateUsing(fn ($state) => collect($state)->map(fn($c) => ucfirst(str_replace('-', ' ', $c)))->join(', ')),

                IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Featured'),

                TextColumn::make('published_at')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('reading_time_minutes')
                    ->suffix(' min read'),
            ])
            ->filters([
                SelectFilter::make('categories')
                    ->options(['blogs' => 'Blogs', 'news' => 'News'])
                    ->query(function ($query, array $data) {
                        if (!empty($data['value'])) {
                            $query->whereJsonContains('categories', $data['value']);
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('published_at', 'desc');
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
            'index' => Pages\ListInsights::route('/'),
            'create' => Pages\CreateInsight::route('/create'),
            'edit' => Pages\EditInsight::route('/{record}/edit'),
        ];
    }
}
