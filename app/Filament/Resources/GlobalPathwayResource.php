<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GlobalPathwayResource\Pages;
use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Models\GlobalPathway;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

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
            Section::make('Basics')->schema([
                Grid::make(3)->schema([
                    Select::make('type')
                        ->options([
                            'pathway-programs' => 'Pathway Programs',
                            'global-opportunities' => 'Global Opportunities',
                        ])
                        ->required(),
                    TextInput::make('title')->required(),
                    TextInput::make('slug')->helperText('URL slug, e.g. pathway-programs'),
                ]),
                TextInput::make('eyebrow')->label('Eyebrow'),
                Grid::make(2)->schema([
                    TextInput::make('heading')->label('Heading'),
                    TextInput::make('heading_italic')->label('Heading (Italic)'),
                ]),
            ]),

            Section::make('Content')->schema([
                RichEditor::make('intro')->columnSpanFull(),
                MediaPicker::forField('image_url', 'global-pathways')->label('Featured Image')->columnSpanFull(),
                Repeater::make('items')
                    ->label('List Items')
                    ->schema([
                        TextInput::make('title')->required(),
                        Textarea::make('desc')->rows(2),
                        TextInput::make('url')->label('Link URL'),
                        TextInput::make('icon')->label('Icon (lucide name)'),
                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
            ]),

            Section::make('SEO')->schema([
                TextInput::make('seo.meta_title')->label('Meta Title'),
                Textarea::make('seo.meta_description')->label('Meta Description')->rows(3),
                Textarea::make('seo.meta_keywords')->label('Meta Keywords')->rows(2),
            ]),

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
                TextColumn::make('type')->badge(),
                TextColumn::make('title')->searchable(),
                TextColumn::make('heading')->limit(30),
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
}
