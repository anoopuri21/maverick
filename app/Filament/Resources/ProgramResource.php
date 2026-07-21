<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProgramResource\Pages;
use App\Filament\Resources\ProgramResource\RelationManagers;
use App\Models\Program;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use App\Services\CloudinaryService;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use App\Filament\Forms\Components\SeoFormFields;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;

class ProgramResource extends Resource
{
    protected static ?string $model = Program::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Program Details')
                    ->tabs([
                        // Tab 1: Basic Information
                        Tab::make('Basic Information')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Grid::make(2)->schema([
                                    Select::make('program_category_id')
                                        ->label('Category')
                                        ->relationship('programCategory', 'name')
                                        ->required()
                                        ->searchable()
                                        ->preload(),

                                    TextInput::make('title')
                                        ->required()
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn ($state, callable $set) => 
                                            $set('slug', \Illuminate\Support\Str::slug($state))
                                        ),

                                    TextInput::make('slug')
                                        ->required()
                                        ->unique(ignoreRecord: true),

                                    TextInput::make('partner_university')
                                        ->label('Partner University'),

                                    TextInput::make('duration'),
                                    TextInput::make('level'),
                                ]),

                                Toggle::make('is_featured')
                                    ->label('Featured on Homepage'),

                                Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true),
                            ]),

                        // Tab 2: Content & Media
                        Tab::make('Content & Media')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Textarea::make('short_description')
                                    ->label('Short Description')
                                    ->rows(3)
                                    ->maxLength(300)
                                    ->helperText('Used in cards & listings.')
                                    ->columnSpanFull(),

                                RichEditor::make('description')
                                    ->label('Full Description')
                                    ->columnSpanFull(),

                                FileUpload::make('image_url')
                                    ->label('Program Image')
                                    ->image()
                                    ->imageEditor()
                                    ->maxSize(5120)
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->helperText('Recommended: 800x540px. Max 5MB.')
                                    ->columnSpanFull()
                                    ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                                        return app(CloudinaryService::class)
                                            ->uploadImage($file->getRealPath(), 'programs');
                                    }),
                            ]),

                        // Tab 3: FAQs
                        Tab::make('FAQs')
                            ->icon('heroicon-o-question-mark-circle')
                            ->schema([
                                \Filament\Forms\Components\Repeater::make('faqs')
                                    ->relationship()
                                    ->schema([
                                        TextInput::make('question')
                                            ->required()
                                            ->columnSpanFull(),

                                        Textarea::make('answer')
                                            ->required()
                                            ->rows(4)
                                            ->columnSpanFull(),

                                        Grid::make(2)->schema([
                                            TextInput::make('sort_order')
                                                ->numeric()
                                                ->default(0),

                                            Toggle::make('is_active')
                                                ->default(true),
                                        ]),
                                    ])
                                    ->orderColumn('sort_order')
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['question'] ?? 'New FAQ')
                                    ->addActionLabel('Add FAQ')
                                    ->columnSpanFull(),
                            ]),

                        // Tab 4: SEO (Reusable Component!)
                        Tab::make('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema(SeoFormFields::make()),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('programCategory.name')
                    ->label('Category')
                    ->sortable(),
                TextColumn::make('partner_university'),
                IconColumn::make('is_featured')
                    ->boolean(),
                IconColumn::make('is_active')
                    ->boolean(),
            ])
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPrograms::route('/'),
            'create' => Pages\CreateProgram::route('/create'),
            'edit' => Pages\EditProgram::route('/{record}/edit'),
        ];
    }
}
