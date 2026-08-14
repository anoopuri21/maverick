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
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use App\Filament\Forms\Components\SeoFormFields;
use App\Filament\Concerns\HandlesCloudinaryImageFields;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\KeyValue;
use App\Filament\Forms\Components\MediaPicker;

class ProgramResource extends Resource
{
    use HandlesCloudinaryImageFields;

    protected static ?string $model = Program::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Global Content';
    protected static ?string $navigationLabel = 'Programs';

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
                                    ->helperText('Used in cards & listings. Plain text (no rich formatting).')
                                    ->columnSpanFull(),

                                RichEditor::make('description')
                                    ->label('Full Description')
                                    ->columnSpanFull(),

                                MediaPicker::forField('image_url', 'programs')
                                    ->label('Program Image')
                                    ->helperText('Recommended: 800x540px. Max 5MB.')
                                    ->columnSpanFull(),
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

                        // Tab 4: Detail Sections (admin-driven content)
                        Tab::make('Detail Sections')
                            ->icon('heroicon-o-squares-2x2')
                            ->schema([
                                // Quick Highlights — list of label/value pairs
                                Repeater::make('highlights')
                                    ->label('Quick Highlights')
                                    ->helperText('Key facts shown near the top.')
                                    ->schema([
                                        TextInput::make('label')->required(),
                                        TextInput::make('value')->required(),
                                    ])
                                    ->collapsible()
                                    ->columns(2)
                                    ->defaultItems(0)
                                    ->addActionLabel('Add Highlight'),

                                // Recognition / Accreditation logos
                                Repeater::make('recognition')
                                    ->label('Recognition & Accreditation Logos')
                                    ->helperText('Logo strip shown under the hero.')
                                    ->schema([
                                        TextInput::make('name')->required(),
                                        TextInput::make('logo')->url()->label('Logo URL'),
                                        RichEditor::make('note')->label('Note (optional)'),
                                    ])
                                    ->collapsible()
                                    ->columns(2)
                                    ->defaultItems(0)
                                    ->addActionLabel('Add Recognition'),

                                // Snapshot ("Programme at a Glance") — label/value pairs
                                Repeater::make('snapshot')
                                    ->label('Programme at a Glance')
                                    ->schema([
                                        TextInput::make('label')->required(),
                                        TextInput::make('value')->required(),
                                    ])
                                    ->collapsible()
                                    ->columns(2)
                                    ->defaultItems(0)
                                    ->addActionLabel('Add Snapshot Item'),

                                // Benefits (Why Choose) — icon + title + desc
                                Repeater::make('benefits')
                                    ->label('Why Choose (Benefits)')
                                    ->schema([
                                        TextInput::make('icon')->label('Icon (lucide name)')->default('sparkles'),
                                        TextInput::make('title')->required(),
                                        RichEditor::make('desc')->label('Description'),
                                    ])
                                    ->collapsible()
                                    ->columns(3)
                                    ->defaultItems(0)
                                    ->addActionLabel('Add Benefit'),

                                // Learning outcomes — simple list
                                Repeater::make('learning')
                                    ->label("What You'll Learn (Outcomes)")
                                    ->schema([
                                        TextInput::make('item')->label('Outcome')->required(),
                                    ])
                                    ->collapsible()
                                    ->defaultItems(0)
                                    ->addActionLabel('Add Outcome'),

                                // Careers — simple list
                                Repeater::make('careers')
                                    ->label('Career Opportunities')
                                    ->schema([
                                        TextInput::make('title')->label('Career Title')->required(),
                                    ])
                                    ->collapsible()
                                    ->defaultItems(0)
                                    ->addActionLabel('Add Career'),

                                // Programme structure — nested Year → Modules → list
                                Repeater::make('structure')
                                    ->label('Programme Structure')
                                    ->schema([
                                        TextInput::make('title')->label('Year Title (e.g. Year 1)')->required(),
                                        TextInput::make('subtitle')->label('Year Subtitle'),
                                        Repeater::make('modules')
                                            ->label('Modules')
                                            ->schema([
                                                TextInput::make('title')->label('Module Name')->required(),
                                                RichEditor::make('overview')->label('Overview'),
                                                Repeater::make('list')
                                                    ->label('Points')
                                                    ->schema([
                                                        TextInput::make('point')->label('Point')->required(),
                                                    ])
                                                    ->collapsible()
                                                    ->defaultItems(0)
                                                    ->addActionLabel('Add Point'),
                                            ])
                                            ->collapsible()
                                            ->defaultItems(0)
                                            ->addActionLabel('Add Module'),
                                    ])
                                    ->collapsible()
                                    ->defaultItems(0)
                                    ->addActionLabel('Add Year'),

                                // Maverick Support — simple list
                                Repeater::make('support')
                                    ->label('Why Study Through Maverick (Support)')
                                    ->schema([
                                        TextInput::make('item')->label('Support Point')->required(),
                                    ])
                                    ->collapsible()
                                    ->defaultItems(0)
                                    ->addActionLabel('Add Support Point'),

                                // About the University
                                Repeater::make('university')
                                    ->label('About the University')
                                    ->schema([
                                        TextInput::make('name')->label('University Name'),
                                        RichEditor::make('description')->label('Description'),
                                        TextInput::make('establishment')->label('Established (e.g. "Established 1985")'),
                                    ])
                                    ->collapsible()
                                    ->columns(2)
                                    ->defaultItems(0)
                                    ->addActionLabel('Add University'),

                                // Accreditation groups — group with nested logo items
                                Repeater::make('accreditation_groups')
                                    ->label('Accreditation & Recognition')
                                    ->schema([
                                        TextInput::make('group')->label('Group Name')->required(),
                                        Repeater::make('items')
                                            ->label('Logos')
                                            ->schema([
                                                TextInput::make('name')->label('Name')->required(),
                                                TextInput::make('logo')->label('Logo URL'),
                                            ])
                                            ->collapsible()
                                            ->columns(2)
                                            ->defaultItems(0)
                                            ->addActionLabel('Add Logo'),
                                    ])
                                    ->collapsible()
                                    ->defaultItems(0)
                                    ->addActionLabel('Add Group'),

                                // Student testimonials (video)
                                Repeater::make('testimonials')
                                    ->label('Student Success Stories (Video)')
                                    ->schema([
                                        TextInput::make('name')->required(),
                                        TextInput::make('role'),
                                        TextInput::make('country'),
                                        TextInput::make('category')->label('Badge (e.g. STUDENT)'),
                                        TextInput::make('thumb')->label('Thumbnail URL'),
                                        TextInput::make('video')->label('Video URL'),
                                    ])
                                    ->collapsible()
                                    ->columns(2)
                                    ->defaultItems(0)
                                    ->addActionLabel('Add Testimonial'),

                                // Fees — simple list
                                Repeater::make('fees')
                                    ->label('Fee Structure Items')
                                    ->schema([
                                        TextInput::make('title')->label('Fee Item')->required(),
                                    ])
                                    ->collapsible()
                                    ->defaultItems(0)
                                    ->addActionLabel('Add Fee Item'),

                                // Reviews (Google ratings)
                                Repeater::make('reviews')
                                    ->label('Student Reviews')
                                    ->schema([
                                        TextInput::make('name')->required(),
                                        TextInput::make('avatar')->label('Avatar URL'),
                                        TextInput::make('rating')->label('Rating (1-5)')->numeric()->minValue(1)->maxValue(5),
                                        RichEditor::make('review')->label('Review Text'),
                                    ])
                                    ->collapsible()
                                    ->columns(2)
                                    ->defaultItems(0)
                                    ->addActionLabel('Add Review'),
                            ]),

                        // Tab 5: SEO (Reusable Component!)
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
