<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Forms\Components\SeoFormFields;
use App\Filament\Resources\ProgramResource\Pages;
use App\Models\MediaAsset;
use App\Models\Program;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProgramResource extends Resource
{
    use HandlesCloudinaryImageFields;

    protected static ?string $model = Program::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Programs';
    protected static ?string $navigationLabel = 'Programs';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Program Details')
                    ->tabs([
                        Tab::make('Basic Information')
                            ->icon('heroicon-o-information-circle')
                            #->description('Core programme identity shown in listings, sticky bar, and hero badges.')
                    ->schema([
                                Grid::make(2)->schema([
                                    Select::make('program_category_id')
                                        ->label('Category')
                                        ->relationship('programCategory', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->validationAttribute('category'),

                                    TextInput::make('title')
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn ($state, Set $set) => $set('slug', \Illuminate\Support\Str::slug($state)))
                                        ->validationAttribute('programme title'),

                                    TextInput::make('slug')
                                        ->helperText('URL slug for /programs/{slug}'),

                                    Select::make('university_partner_id')
                                        ->label('University Partner')
                                        ->relationship('universityPartner', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->placeholder('Select a university (or leave empty)')
                                        ->helperText('Select the university offering this program. University details are managed once under University Partners — no need to re-type.'),


                                    TextInput::make('duration')
                                        ->placeholder('e.g. 20–24 Months')
                                        ->helperText('Shown in hero meta row.'),

                                    TextInput::make('level')
                                        ->placeholder('e.g. Undergraduate')
                                        ->helperText('Shown in hero badge.'),

                                    TextInput::make('sort_order')
                                        ->label('Sort Order')
                                        ->numeric()->nullable()
                                        ->default(0)
                                        ->helperText('Lower numbers appear first in admin list and listings.'),
                                ]),

                                Grid::make(2)->schema([
                                    Toggle::make('is_featured')
                                        ->label('Featured on Homepage'),

                                    Toggle::make('is_active')
                                        ->label('Active')
                                        ->default(true)
                                        ->helperText('Inactive programmes are hidden from the public site.'),
                                ]),
                            ]),

                        Tab::make('Hero & Media')
                            ->icon('heroicon-o-photo')
                            #->description('Hero background, overview copy, and brochure. Powers sections 1 (Hero) and 4 (Overview).')
                    ->schema([
                                Textarea::make('short_description')
                                    ->label('Short Description')
                                    ->rows(3)
                                    ->maxLength(300)
                                    ->placeholder('A concise summary for the hero and listing cards.')
                                    ->helperText('Plain text only. Shown as the hero lead paragraph.')
                                    ->columnSpanFull(),

                                RichEditor::make('description')
                                    ->label('Programme Overview')
                                    ->helperText('Full overview copy shown in the Programme Overview section.')
                                    ->columnSpanFull(),

                                Section::make('Hero Image')
                                    #->description('Background image for the cinematic hero. Falls back to a default if empty.')
                    ->schema([
                                        TextInput::make('image_url')
                                            ->label('Hero Image URL')
                                            ->nullable()
                                            ->helperText('Recommended: 800×540px. Or choose from the media library below.')
                                            ->columnSpanFull(),
                                        MediaPicker::forField('image_url', 'programs')
                    ->label('Hero Image')
                    ->columnSpanFull(),
                                    ]),

                                TextInput::make('brochure_url')
                                    ->label('Brochure URL')
                                    ->nullable()
                                    ->placeholder('https://...')
                                    ->helperText('Optional. When set, a Download Brochure button can show in the hero.')
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('Programme Sections')
                            ->icon('heroicon-o-squares-2x2')
                            #'Content blocks on the programme detail page, in page order. Empty sections are hidden publicly.')
                    ->schema([
                                Section::make('Quick Highlights')
                                    #->description('Tick-list shown in the hero (§1). Also used in the overview figure stat.')
                    ->collapsible()
                    ->collapsed(false)
                    ->schema([static::highlightsRepeater()]),

                                Section::make('Programme Snapshot')
                                    #->description('Bento grid tiles (§3) and first six items in the hero Programme at a Glance card.')
                    ->collapsible()
                    ->collapsed(true)
                    ->schema([static::snapshotRepeater()]),

                                Section::make('Why Choose This Programme')
                                    #->description('Pillar cards in the Why Choose section (§5).')
                    ->collapsible()
                    ->collapsed(true)
                    ->schema([static::benefitsRepeater()]),

                                Section::make("What You'll Learn")
                                    #->description('Learning outcome items (§6).')
                    ->collapsible()
                    ->collapsed(true)
                    ->schema([static::learningRepeater()]),

                                Section::make('Career Opportunities')
                                    #->description('Career cloud tiles (§7).')
                    ->collapsible()
                    ->collapsed(true)
                    ->schema([static::careersRepeater()]),

                                Section::make('Programme Structure')
                                    #->description('Year-by-year accordion with modules (§8). Module titles shown as a list on the public page.')
                    ->collapsible()
                    ->collapsed(true)
                    ->schema([static::structureRepeater()]),

                                Section::make('Awarding University')
                                    ->description('The university offering this program is selected above under Basic Information (University Partner). Its name, description and image are managed once in University Partners and shown in the About-the-University section and recognition header.')
                                    ->collapsible()
                                    ->collapsed(true)
                                    ->schema([
                                        \Filament\Forms\Components\Placeholder::make('university_link')
                                            ->label('University Partner')
                                            ->content(fn ($record) => $record?->universityPartner?->name ?? 'No university linked yet.'),
                                    ]),

                                Section::make('Why Study Through Maverick')
                                    #->description('Support perk grid (§11). All cards look identical — no featured item.')
                    ->collapsible()
                    ->collapsed(true)
                    ->schema([static::supportRepeater()]),

                                Section::make('Why GCC Professionals Choose This Course')
                                    ->description('Reason cards shown below the Maverick support section. Leave heading blank to use the default public title.')
                    ->collapsible()
                    ->collapsed(true)
                    ->schema([
                        TextInput::make('gcc_heading')
                            ->label('Section Heading')
                            ->placeholder('Why GCC professionals choose this course?')
                            ->helperText('Optional. Leave blank to use the default heading on the public page.')
                            ->columnSpanFull(),
                        static::gccReasonsRepeater(),
                    ]),

                                Section::make('Student Success Stories')
                                    #->description('Video testimonial slider (§12).')
                    ->collapsible()
                    ->collapsed(true)
                    ->schema([static::testimonialsRepeater()]),

                                Section::make('Fees & Scholarships')
                                    #->description('Fee chips linking to enquiry (§13).')
                    ->collapsible()
                    ->collapsed(true)
                    ->schema([static::feesRepeater()]),

                                Section::make('Student Reviews')
                                    #->description('Google-style review cards (§16). Avatar photo optional — initials used as fallback.')
                    ->collapsible()
                    ->collapsed(true)
                    ->schema([static::reviewsRepeater()]),
                            ]),

                        Tab::make('Accreditation & Recognition')
                            ->icon('heroicon-o-check-badge')
                            #->description('Logo marquee under the hero (§2) and grouped accreditation grid (§10).')
                    ->schema([
                                Section::make('Recognition Marquee')
                                    #->description('Scrolling logo strip shown directly under the hero.')
                    ->collapsible()
                    ->collapsed(false)
                    ->schema([static::recognitionRepeater()]),

                                Section::make('Accreditation Groups')
                                    #->description('Grouped logo grids in the Accreditation & Recognition section.')
                    ->collapsible()
                    ->collapsed(true)
                    ->schema([static::accreditationGroupsRepeater()]),
                            ]),

                        Tab::make('FAQs')
                            ->icon('heroicon-o-question-mark-circle')
                            #->description('Accordion on the programme page (§14). Only active FAQs are shown.')
                    ->schema([
                                Repeater::make('faqs')
                                    ->relationship()
                                    ->schema([
                                        TextInput::make('question')
                                            ->validationAttribute('question')
                                            ->columnSpanFull(),

                                        RichEditor::make('answer')
                                            ->validationAttribute('answer')
                                            ->columnSpanFull(),

                                        Grid::make(2)->schema([
                                            TextInput::make('sort_order')
                                                ->numeric()->nullable()
                                                ->default(0),

                                            Toggle::make('is_active')
                                                ->label('Active')
                                                ->default(true),
                                        ]),
                                    ])
                                    ->orderColumn('sort_order')
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['question'] ?? 'New FAQ')
                                    ->addActionLabel('Add FAQ')
                                    ->columnSpanFull(),
                            ]),

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
                ImageColumn::make('image_url')
                    ->label('Hero')
                    ->size(40),

                TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->limit(40),

                TextColumn::make('programCategory.name')
                    ->label('Category')
                    ->sortable(),

                TextColumn::make('universityPartner.name')
                    ->label('University')
                    ->searchable()
                    ->toggleable()
                    ->placeholder('—')
                    ->limit(30),

                TextColumn::make('duration')
                    ->toggleable(),

                TextColumn::make('level')
                    ->toggleable(),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),

                IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Featured'),

                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
            ])
            ->defaultSort('sort_order')
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active'),

                TernaryFilter::make('is_featured')
                    ->label('Featured'),

                SelectFilter::make('program_category_id')
                    ->label('Category')
                    ->relationship('programCategory', 'name'),
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
            'index' => Pages\ListPrograms::route('/'),
            'create' => Pages\CreateProgram::route('/create'),
            'edit' => Pages\EditProgram::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->with(['programCategory:id,name', 'universityPartner:id,name']);
    }

    /**
     * Strip transient MediaPicker keys and sync nested media URLs before save.
     */
    public static function cleanJsonForSave(array $data): array
    {
        foreach ($data['recognition'] ?? [] as &$row) {
            static::syncNestedMediaField($row, 'logo');
        }
        unset($row);

        foreach ($data['testimonials'] ?? [] as &$row) {
            static::syncNestedMediaField($row, 'thumb');
        }
        unset($row);

        foreach ($data['reviews'] ?? [] as &$row) {
            static::syncNestedMediaField($row, 'avatar');
        }
        unset($row);

        foreach ($data['accreditation_groups'] ?? [] as &$group) {
            foreach ($group['items'] ?? [] as &$item) {
                static::syncNestedMediaField($item, 'logo');
            }
            unset($item);
        }
        unset($group);

        if (isset($data['recognition'])) {
            static::stripAssetIdKeys($data['recognition']);
        }
        if (isset($data['testimonials'])) {
            static::stripAssetIdKeys($data['testimonials']);
        }
        if (isset($data['reviews'])) {
            static::stripAssetIdKeys($data['reviews']);
        }

        foreach ($data['accreditation_groups'] ?? [] as &$group) {
            if (isset($group['items'])) {
                static::stripAssetIdKeys($group['items']);
            }
        }
        unset($group);

        return $data;
    }

    protected static function syncNestedMediaField(array &$row, string $field): void
    {
        $assetKey = "{$field}_asset_id";

        if (! array_key_exists($assetKey, $row)) {
            return;
        }

        if (! empty($row[$assetKey])) {
            $asset = MediaAsset::query()->find($row[$assetKey]);

            if ($asset) {
                $row[$field] = $asset->url;
            }
        } elseif (empty($row[$field])) {
            $row[$field] = null;
        }

        unset($row[$assetKey]);
    }

    protected static function stripAssetIdKeys(array &$rows): void
    {
        foreach ($rows as &$row) {
            foreach (array_keys($row) as $key) {
                if (str_ends_with($key, '_asset_id')) {
                    unset($row[$key]);
                }
            }
        }
        unset($row);
    }

    /** @return array<string, string> */
    protected static function lucideIconOptions(): array
    {
        return [
            'users' => 'users — Leadership / teams',
            'book-open' => 'book-open — Curriculum',
            'globe' => 'globe — International',
            'trending-up' => 'trending-up — Career growth',
            'laptop' => 'laptop — Flexible learning',
            'sparkles' => 'sparkles — Highlights',
            'shield' => 'shield — Accreditation',
            'award' => 'award — Recognition',
            'graduation-cap' => 'graduation-cap — Graduation',
            'briefcase' => 'briefcase — Business',
            'target' => 'target — Goals',
            'lightbulb' => 'lightbulb — Innovation',
            'heart-handshake' => 'heart-handshake — Support',
            'clock' => 'clock — Duration',
            'map-pin' => 'map-pin — Location',
            'monitor' => 'monitor — Online learning',
            'route' => 'route — Pathway / progression',
            'badge-check' => 'badge-check — Verified qualification',
            'landmark' => 'landmark — Regional / national vision',
        ];
    }

    protected static function highlightsRepeater(): Repeater
    {
        return Repeater::make('highlights')
            ->schema([
                TextInput::make('label')
                    ->placeholder('e.g. Duration')
                    ->validationAttribute('highlight label'),
                TextInput::make('value')
                    ->placeholder('e.g. 20–24 Months')
                    ->validationAttribute('highlight value'),
            ])
            ->reorderable()
            ->collapsible()
            ->columns(2)
            ->defaultItems(0)
            ->itemLabel(fn (array $state): ?string => $state['value'] ?? $state['label'] ?? 'Highlight')
            ->addActionLabel('Add Highlight');
    }

    protected static function snapshotRepeater(): Repeater
    {
        return Repeater::make('snapshot')
            ->schema([
                TextInput::make('label')
                    ->placeholder('e.g. Degree Award')
                    ->validationAttribute('snapshot label'),
                TextInput::make('value')
                    ->placeholder('e.g. BSc')
                    ->validationAttribute('snapshot value'),
            ])
            ->reorderable()
            ->collapsible()
            ->columns(2)
            ->defaultItems(0)
            ->itemLabel(fn (array $state): ?string => $state['label'] ?? 'Snapshot item')
            ->addActionLabel('Add Snapshot Item');
    }

    protected static function benefitsRepeater(): Repeater
    {
        return Repeater::make('benefits')
            ->schema([
                Grid::make(2)->schema([
                    Select::make('icon_preset')
                        ->label('Icon preset')
                        ->options(static::lucideIconOptions())
                        ->searchable()
                        ->dehydrated(false)
                        ->live()
                        ->afterStateUpdated(fn ($state, Set $set) => filled($state) ? $set('icon', $state) : null)
                        ->helperText('Pick a common Lucide icon, or enter a custom name below.'),

                    TextInput::make('icon')
                        ->label('Icon name (Lucide)')
                        ->default('sparkles')
                        ->placeholder('e.g. book-open-check')
                        ->helperText('Any Lucide icon name. Overrides the preset when typed manually.'),
                ]),
                TextInput::make('title')
                    ->placeholder('e.g. Develop Leadership Skills')
                    ->validationAttribute('benefit title')
                    ->columnSpanFull(),
                RichEditor::make('desc')
                    ->label('Description')
                    ->columnSpanFull(),
            ])
            ->reorderable()
            ->collapsible()
            ->defaultItems(0)
            ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'Benefit')
            ->addActionLabel('Add Benefit');
    }

    protected static function learningRepeater(): Repeater
    {
        return Repeater::make('learning')
            ->schema([
                TextInput::make('item')
                    ->label('Outcome')
                    ->placeholder('e.g. Develop strategic thinking')
                    ->validationAttribute('learning outcome'),
            ])
            ->reorderable()
            ->collapsible()
            ->defaultItems(0)
            ->itemLabel(fn (array $state): ?string => $state['item'] ?? 'Outcome')
            ->addActionLabel('Add Outcome');
    }

    protected static function careersRepeater(): Repeater
    {
        return Repeater::make('careers')
            ->schema([
                TextInput::make('title')
                    ->label('Career Title')
                    ->placeholder('e.g. Business Manager')
                    ->validationAttribute('career title'),
            ])
            ->reorderable()
            ->collapsible()
            ->defaultItems(0)
            ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'Career')
            ->addActionLabel('Add Career');
    }

    protected static function structureRepeater(): Repeater
    {
        return Repeater::make('structure')
            ->schema([
                TextInput::make('title')
                    ->label('Year Title')
                    ->placeholder('Year 1')
                    ->validationAttribute('year title'),
                TextInput::make('subtitle')
                    ->label('Year Subtitle')
                    ->placeholder('Business Foundations'),
                Repeater::make('modules')
                    ->label('Modules')
                    ->schema([
                        TextInput::make('title')
                            ->label('Module Name')
                            ->validationAttribute('module title'),
                        RichEditor::make('overview')
                            ->label('Overview')
                            ->helperText('Optional extended copy (not shown in the public list view).'),
                        RichEditor::make('desc')
                            ->label('Description')
                            ->helperText('Optional alternate description (stored for future use).'),
                        Repeater::make('list')
                            ->label('Key Points')
                            ->schema([
                                TextInput::make('point')
                                    ->label('Point')
                                    ->validationAttribute('module point'),
                            ])
                    ->collapsible()
                    ->collapsed(true)
                    ->defaultItems(0)
                    ->itemLabel(fn (array $state): ?string => $state['point'] ?? 'Point')
                    ->addActionLabel('Add Point'),
                    ])
                    ->collapsible()
                    ->collapsed(true)
                    ->defaultItems(0)
                    ->itemLabel(fn (array $state): ?string => isset($state['title']) ? 'Module: '.$state['title'] : 'Module')
                    ->addActionLabel('Add Module'),
            ])
            ->reorderable()
            ->collapsible()
            ->defaultItems(0)
            ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'Year')
            ->addActionLabel('Add Year');
    }

    protected static function supportRepeater(): Repeater
    {
        return Repeater::make('support')
            ->schema([
                TextInput::make('item')
                    ->label('Support Point')
                    ->placeholder('e.g. Dedicated Academic Support')
                    ->validationAttribute('support point'),
            ])
            ->reorderable()
            ->collapsible()
            ->defaultItems(0)
            ->itemLabel(fn (array $state): ?string => $state['item'] ?? 'Support point')
            ->addActionLabel('Add Support Point');
    }

    protected static function gccReasonsRepeater(): Repeater
    {
        return Repeater::make('gcc_reasons')
            ->schema([
                Grid::make(2)->schema([
                    Select::make('icon_preset')
                        ->label('Icon preset')
                        ->options(static::lucideIconOptions())
                        ->searchable()
                        ->dehydrated(false)
                        ->live()
                        ->afterStateUpdated(fn ($state, Set $set) => filled($state) ? $set('icon', $state) : null)
                        ->helperText('Pick a common Lucide icon, or enter a custom name below.'),

                    TextInput::make('icon')
                        ->label('Icon name (Lucide)')
                        ->default('sparkles')
                        ->placeholder('e.g. monitor')
                        ->helperText('Any Lucide icon name. Overrides the preset when typed manually.'),
                ]),
                TextInput::make('title')
                    ->label('List Heading')
                    ->placeholder('e.g. 100% Online Learning for GCC Professionals')
                    ->validationAttribute('reason title')
                    ->columnSpanFull(),
                Textarea::make('text')
                    ->label('List Content')
                    ->rows(3)
                    ->placeholder('Short supporting copy for this reason.')
                    ->validationAttribute('reason text')
                    ->columnSpanFull(),
            ])
            ->reorderable()
            ->collapsible()
            ->defaultItems(0)
            ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'GCC reason')
            ->addActionLabel('Add GCC Reason');
    }

    protected static function testimonialsRepeater(): Repeater
    {
        return Repeater::make('testimonials')
            ->schema([
                TextInput::make('name')
                    ->validationAttribute('student name'),
                TextInput::make('role')
                    ->placeholder('e.g. MBA Graduate'),
                TextInput::make('country')
                    ->placeholder('e.g. UAE'),
                TextInput::make('category')
                    ->label('Badge')
                    ->placeholder('e.g. STUDENT'),
                TextInput::make('video')
                    ->label('YouTube Video URL')
                    ->nullable()
                    ->placeholder('https://www.youtube.com/watch?v=xxxxx or https://youtu.be/xxxxx')
                    ->helperText('Paste YouTube link — thumbnail auto-generate ho jayega.')
                    ->live()
                    ->columnSpanFull(),
                Placeholder::make('thumb_preview')
                    ->label('Thumbnail preview')
                    ->content(function (Get $get): HtmlString {
                        $src = youtube_thumbnail_url($get('video'), $get('thumb'));
                        if (! $src) {
                            return new HtmlString('<p style="color:#6b7280;font-size:13px;margin:0">Paste a YouTube URL to auto-load the thumbnail, or upload a custom image below.</p>');
                        }
                        $fallback = youtube_thumbnail_fallback($get('video'));
                        $onerror = $fallback && $fallback !== $src
                            ? ' onerror="if(this.dataset.retry){this.src=this.dataset.retry;delete this.dataset.retry;}" data-retry="'.e($fallback).'"'
                            : '';

                        return new HtmlString('<img src="'.e($src).'" alt="YouTube thumbnail preview" style="max-width:320px;width:100%;border-radius:12px;display:block"'.$onerror.'>');
                    })
                    ->columnSpanFull(),
                TextInput::make('thumb')
                    ->label('Custom Thumbnail URL (Optional)')
                    ->nullable()
                    ->live()
                    ->helperText('Leave empty to auto-use the YouTube thumbnail.'),
                MediaPicker::forField('thumb', 'programs/testimonials')
                    ->label('Custom Thumbnail Image (Optional)')
                    ->helperText('Optional: upload a custom thumbnail. YouTube thumbnail will be auto-used if empty.'),
            ])
            ->reorderable()
            ->collapsible()
            ->columns(2)
            ->defaultItems(0)
            ->itemLabel(fn (array $state): ?string => $state['name'] ?? 'Testimonial')
            ->addActionLabel('Add Testimonial');
    }

    protected static function feesRepeater(): Repeater
    {
        return Repeater::make('fees')
            ->schema([
                TextInput::make('title')
                    ->label('Fee Item')
                    ->placeholder('e.g. Tuition Fees')
                    ->validationAttribute('fee item'),
            ])
            ->reorderable()
            ->collapsible()
            ->defaultItems(0)
            ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'Fee item')
            ->addActionLabel('Add Fee Item');
    }

    protected static function reviewsRepeater(): Repeater
    {
        return Repeater::make('reviews')
            ->schema([
                TextInput::make('name')
                    ->validationAttribute('reviewer name'),
                TextInput::make('avatar')
                    ->label('Avatar URL')
                    ->nullable()
                    ->helperText('Optional. Or choose from the media library below.'),
                MediaPicker::forField('avatar', 'programs/reviews')
                    ->label('Avatar Image'),
                Select::make('rating')
                    ->options([
                        1 => '1 star',
                        2 => '2 stars',
                        3 => '3 stars',
                        4 => '4 stars',
                        5 => '5 stars',
                    ])
                    ->default(5)
                    ->validationAttribute('rating'),
                RichEditor::make('review')
                    ->label('Review Text')
                    ->columnSpanFull(),
            ])
            ->reorderable()
            ->collapsible()
            ->columns(2)
            ->defaultItems(0)
            ->itemLabel(fn (array $state): ?string => $state['name'] ?? 'Review')
            ->addActionLabel('Add Review');
    }

    protected static function recognitionRepeater(): Repeater
    {
        return Repeater::make('recognition')
            ->schema([
                TextInput::make('name')
                    ->validationAttribute('organisation name'),
                TextInput::make('logo')
                    ->label('Logo URL')
                    ->nullable()
                    ->helperText('Or choose from the media library below.'),
                MediaPicker::forField('logo', 'programs/recognition')
                    ->label('Logo Image'),
                RichEditor::make('note')
                    ->label('Note (optional)')
                    ->helperText('Short caption shown under the name in the marquee.')
                    ->columnSpanFull(),
            ])
            ->reorderable()
            ->collapsible()
            ->columns(2)
            ->defaultItems(0)
            ->itemLabel(fn (array $state): ?string => $state['name'] ?? 'Recognition')
            ->addActionLabel('Add Recognition Logo');
    }

    protected static function accreditationGroupsRepeater(): Repeater
    {
        return Repeater::make('accreditation_groups')
            ->schema([
                TextInput::make('group')
                    ->label('Group Name')
                    ->placeholder('e.g. International Accreditation')
                    ->validationAttribute('group name'),
                Repeater::make('items')
                    ->label('Logos')
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->validationAttribute('logo name'),
                        TextInput::make('logo')
                            ->label('Logo URL')
                            ->nullable()
                            ->helperText('Or choose from the media library below.'),
                        MediaPicker::forField('logo', 'programs/accreditation')
                    ->label('Logo Image'),
                    ])
                    ->reorderable()
                    ->collapsible()
                    ->columns(2)
                    ->defaultItems(0)
                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? 'Logo')
                    ->addActionLabel('Add Logo'),
            ])
            ->reorderable()
            ->collapsible()
            ->defaultItems(0)
            ->itemLabel(fn (array $state): ?string => $state['group'] ?? 'Group')
            ->addActionLabel('Add Group');
    }
}
