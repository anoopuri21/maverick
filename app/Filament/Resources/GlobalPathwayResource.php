<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\GlobalPathwayResource\Pages;
use App\Models\GlobalPathway;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class GlobalPathwayResource extends Resource
{
    use HandlesCloudinaryImageFields;

    protected static ?string $model = GlobalPathway::class;
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationGroup = 'Global Pathways';
    protected static ?string $navigationLabel = 'Pathway Pages';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Tabs::make('Pathway Page')
                ->tabs([
                    Tab::make('Basics')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Grid::make(3)->schema([
                                Select::make('type')
                                    ->options([
                                        'pathway-programs' => 'Pathway Programs',
                                        'global-opportunities' => 'Global Opportunities',
                                    ])
                                    ->required()
                                    ->helperText('Defines which page this content belongs to.'),
                                TextInput::make('title')->required(),
                                TextInput::make('slug')
                                    ->helperText('URL slug (e.g. pathway-programs). Drives the frontend route.')
                                    ->unique(ignoreRecord: true)
                                    ->required(),
                            ]),
                            TextInput::make('eyebrow')->label('Eyebrow'),
                            Grid::make(2)->schema([
                                TextInput::make('heading')->label('Heading'),
                                TextInput::make('heading_italic')->label('Heading (Italic)'),
                            ]),
                        ]),

                    Tab::make('Content')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            RichEditor::make('intro')->columnSpanFull(),
                            MediaPicker::forField('image_url', 'global-pathways')->label('Featured Image')->columnSpanFull(),
                            Repeater::make('items')
                                ->label('Card Items')
                                ->schema([
                                    Grid::make(2)->schema([
                                        TextInput::make('title')->required(),
                                        Select::make('icon')
                                            ->options(self::lucideIconOptions())
                                            ->searchable()
                                            ->default('sparkles'),
                                    ]),
                                    Textarea::make('desc')->label('Description')->rows(2)->columnSpanFull(),
                                    TextInput::make('url')->label('Link URL')->columnSpanFull(),
                                ])
                                ->reorderable()
                                ->collapsible()
                                ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                                ->columnSpanFull(),
                        ]),

                    Tab::make('SEO')
                        ->icon('heroicon-o-magnifying-glass')
                        ->schema([
                            TextInput::make('seo.meta_title')->label('Meta Title'),
                            Textarea::make('seo.meta_description')->label('Meta Description')->rows(3),
                            Textarea::make('seo.meta_keywords')->label('Meta Keywords')->rows(2),
                        ]),
                ])
                ->columnSpanFull(),

            Grid::make(2)->schema([
                Toggle::make('is_active')->default(true),
                TextInput::make('sort_order')->numeric()->default(0),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_url')->label('Image')->size(44)->circular(),
                TextColumn::make('type')->badge(),
                TextColumn::make('title')->searchable(),
                TextColumn::make('slug')->searchable()->copyable(),
                TextColumn::make('items')
                    ->label('Cards')
                    ->formatStateUsing(fn ($state) => is_array($state) ? count($state) : 0),
                TextColumn::make('sort_order')->sortable(),
                ToggleColumn::make('is_active'),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'pathway-programs' => 'Pathway Programs',
                        'global-opportunities' => 'Global Opportunities',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGlobalPathways::route('/'),
            'create' => Pages\CreateGlobalPathway::route('/create'),
            'edit' => Pages\EditGlobalPathway::route('/{record}/edit'),
        ];
    }

    /** @return array<string, string> */
    protected static function lucideIconOptions(): array
    {
        return [
            'sparkles' => 'sparkles — Highlights',
            'graduation-cap' => 'graduation-cap — Degree',
            'layers' => 'layers — Dual',
            'rocket' => 'rocket — Progression',
            'award' => 'award — Recognition',
            'globe' => 'globe — Global',
            'users' => 'users — Exchange',
            'briefcase' => 'briefcase — Career',
            'trending-up' => 'trending-up — Growth',
            'laptop' => 'laptop — Online',
            'book-open' => 'book-open — Learning',
            'compass' => 'compass — Pathways',
        ];
    }
}
