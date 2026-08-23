<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Resources\BlogPostResource\Pages;
use App\Filament\Resources\BlogPostResource\RelationManagers;
use App\Models\BlogPost;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use App\Filament\Forms\Components\MediaPicker;

class BlogPostResource extends Resource
{
    use HandlesCloudinaryImageFields;

    protected static ?string $model = BlogPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Global Content';
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Content')->schema([
                TextInput::make('title')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, Set $set, Get $get) {
                        // Only auto-fill slug if it's currently empty
                        // (don't overwrite a manually-edited slug)
                        if (blank($get('slug'))) {
                            $set('slug', Str::slug($state));
                        }
                    }),

                TextInput::make('slug')
                    
                    ->maxLength(255),

                Textarea::make('excerpt')
                    ->maxLength(500)
                    ->helperText('Short summary shown on the blog listing page (max 500 characters).')
                    ->rows(3),

                RichEditor::make('content')
                    ->columnSpanFull()
                    ->fileAttachmentsDirectory('blog-content-images'),
            ]),

            Section::make('Media')->schema([
                MediaPicker::forField('featured_image_url', 'blog-images')
                    ->label('Featured Image')
                    ->helperText('Optional — if left empty, a branded typographic cover will be shown automatically on the site.'),

                TextInput::make('featured_image_alt')
                    ->label('Image Alt Text')
                    ->helperText('Used for SEO & accessibility. Recommended if an image is uploaded.'),
            ]),

            Section::make('Organization')->schema([
                Select::make('category')
                    ->options(['Blogs' => 'Blogs'])
                    ->default('Blogs')
                    ->native(false),

                TagsInput::make('tags')
                    ->helperText('Press enter after each tag.'),

                // IMPORTANT: single-featured-post enforcement
                Toggle::make('is_featured')
                    ->label('Show as Featured Post')
                    ->helperText('Only ONE post can be featured at a time. Enabling this will automatically un-feature any other currently featured post.')
                    ->live()
                    ->afterStateUpdated(function ($state, $record) {
                        if ($state === true) {
                            BlogPost::where('is_featured', true)
                    ->when($record, fn ($query) => $query->where('id', '!=', $record->id))
                    ->update(['is_featured' => false]);
                        }
                    }),
            ]),

            Section::make('Author')->schema([
                TextInput::make('author_name')
                    ->default('Maverick Business Academy')
                    ,

                TextInput::make('author_avatar_url')
                    ->label('Author Avatar URL')
                    ->url()->nullable(),

                RichEditor::make('author_bio')
                    ->maxLength(500)
                    ,
            ]),

            Section::make('Publishing')->schema([
                DateTimePicker::make('published_at')
                    ->default(now())
                    ,

                TextInput::make('reading_time_minutes')
                    ->numeric()->nullable()
                    ->minValue(1)
                    ->suffix('minutes')
                    ->helperText('Auto-calculated during import; override manually if needed.'),
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

                TextColumn::make('category')
                    ->badge(),

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
                Tables\Filters\SelectFilter::make('category')
                    ->options(['Blogs' => 'Blogs']),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured Status'),
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
            'index' => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }
}
