<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UniversityPartnerResource\Pages;
use App\Filament\Resources\UniversityPartnerResource\RelationManagers;
use App\Models\MediaAsset;
use App\Models\UniversityPartner;
use App\Filament\Concerns\HandlesCloudinaryImageFields;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Forms\Components\MediaPicker;

class UniversityPartnerResource extends Resource
{
    use HandlesCloudinaryImageFields;

    protected static ?string $model = UniversityPartner::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Global Content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Section::make('Basic Info')
                    ->schema([
                        \Filament\Forms\Components\Grid::make(2)->schema([
                            \Filament\Forms\Components\TextInput::make('name')
                                ,
                            \Filament\Forms\Components\TextInput::make('country')
                                ,
                            \Filament\Forms\Components\TextInput::make('country_code')
                                ->maxLength(3)
                                ->helperText('e.g. UAE, UK, USA'),
                            \Filament\Forms\Components\TextInput::make('city'),
                        ]),
                    ]),

                \Filament\Forms\Components\Section::make('Location (for Map)')
                    ->schema([
                        \Filament\Forms\Components\Grid::make(3)->schema([
                            \Filament\Forms\Components\TextInput::make('latitude')
                                ->numeric()
                                ->nullable()
                                ->minValue(-90)
                                ->maxValue(90)
                                ->step('any')
                                ->helperText('e.g. 25.2048'),
                            \Filament\Forms\Components\TextInput::make('longitude')
                                ->numeric()
                                ->nullable()
                                ->minValue(-180)
                                ->maxValue(180)
                                ->step('any')
                                ->helperText('e.g. 55.2708'),
                            \Filament\Forms\Components\Toggle::make('is_hub')
                                ->label('Main Hub')
                                ->helperText('Highlight this as hub'),
                        ]),
                    ]),

                \Filament\Forms\Components\Section::make('Details')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('recognition')
                            ->helperText('e.g. AACSB Accredited, QAA Reviewed')
                            ->columnSpanFull(),
                        MediaPicker::forField('logo_url', 'university-partners')
                            ->label('Logo')
                            ->columnSpanFull(),
                        MediaPicker::forField('campus_image_url', 'university-partners/campuses')
                            ->label('Campus image')
                            ->helperText('Shown in the Program Overview and About the University sections on linked program detail pages.')
                            ->columnSpanFull(),
                        \Filament\Forms\Components\TextInput::make('website_url')->nullable(),
                        \Filament\Forms\Components\RichEditor::make('description')->columnSpanFull(),
                    ]),

                \Filament\Forms\Components\Section::make('Recognised & Accredited')
                    ->description('Used on every linked program page: logos only in the Recognised & Accredited slider, and logo plus name in Accreditation & Recognition.')
                    ->schema([
                        \Filament\Forms\Components\Repeater::make('recognition_logos')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('name')
                                    ->validationAttribute('organisation name'),
                                \Filament\Forms\Components\TextInput::make('logo')
                                    ->label('Logo URL')
                                    ->nullable()
                                    ->helperText('Or choose from the media library below.'),
                                MediaPicker::forField('logo', 'university-partners/recognition')
                                    ->label('Logo Image'),
                                \Filament\Forms\Components\RichEditor::make('note')
                                    ->label('Note (optional)')
                                    ->helperText('Short caption shown under the name in the marquee.')
                                    ->columnSpanFull(),
                            ])
                            ->reorderable()
                            ->collapsible()
                            ->columns(2)
                            ->defaultItems(0)
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? 'Recognition')
                            ->addActionLabel('Add Recognition Logo')
                            ->columnSpanFull(),
                    ]),

                \Filament\Forms\Components\Section::make('URL & Programs')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('slug')
                            ->label('Slug / Initials')
                            ->helperText('Unique URL identifier (e.g. gau). Used to disambiguate programs with the same name across universities.')
                            ->columnSpanFull(),
                        \Filament\Forms\Components\Placeholder::make('programs_link')
                            ->label('Programs Offered')
                            ->content(function ($record) {
                                if (! $record) {
                                    return 'Save this university first, then link programs from the Programs resource.';
                                }
                                $count = $record->programs()->count();
                                if ($count === 0) {
                                    return 'No programs linked yet. Link them from the Programs resource (University Partner dropdown).';
                                }
                                return $record->programs()->pluck('title')->implode(' · ');
                            })
                    ->columnSpanFull(),
                    ]),

                \Filament\Forms\Components\Section::make('Display')
                    ->schema([
                        \Filament\Forms\Components\Grid::make(2)->schema([
                            \Filament\Forms\Components\TextInput::make('sort_order')
                                ->numeric()->nullable()
                                ->default(0),
                            \Filament\Forms\Components\Toggle::make('is_active')
                                ->default(true),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('country'),
                ImageColumn::make('logo_url')
                    ->label('Logo')
                    ->size(50),
                ImageColumn::make('campus_image_url')
                    ->label('Campus')
                    ->size(50),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
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

    /**
     * Strip transient MediaPicker keys and sync nested logo URLs before save.
     */
    public static function cleanRecognitionLogos(array $data): array
    {
        if (! array_key_exists('recognition_logos', $data) || ! is_array($data['recognition_logos'])) {
            return $data;
        }

        foreach ($data['recognition_logos'] as &$row) {
            if (! is_array($row)) {
                continue;
            }

            $assetKey = 'logo_asset_id';

            if (array_key_exists($assetKey, $row)) {
                if (! empty($row[$assetKey])) {
                    $asset = MediaAsset::query()->find($row[$assetKey]);

                    if ($asset) {
                        $row['logo'] = $asset->url;
                    }
                } elseif (empty($row['logo'])) {
                    $row['logo'] = null;
                }

                unset($row[$assetKey]);
            }

            foreach (array_keys($row) as $key) {
                if (str_ends_with((string) $key, '_asset_id')) {
                    unset($row[$key]);
                }
            }
        }
        unset($row);

        $data['recognition_logos'] = array_values($data['recognition_logos']);

        return $data;
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
            'index' => Pages\ListUniversityPartners::route('/'),
            'create' => Pages\CreateUniversityPartner::route('/create'),
            'edit' => Pages\EditUniversityPartner::route('/{record}/edit'),
        ];
    }
}
