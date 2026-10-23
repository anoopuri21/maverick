<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\OurStoryTestimonialResource\Pages;
use App\Models\OurStoryTestimonial;
use App\Support\TestimonialPages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OurStoryTestimonialResource extends Resource
{
    protected static ?string $model = OurStoryTestimonial::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Global Content';

    protected static ?string $navigationLabel = 'Testimonials';

    protected static ?string $modelLabel = 'Testimonial';

    protected static ?string $pluralModelLabel = 'Testimonials';

    protected static ?int $navigationSort = 3;

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Student / Author Details')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Student / Author Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('position')
                            ->label('Role / Course / Designation')
                            ->placeholder('e.g. Global MBA · UCA or Senior Manager')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('organisation')
                            ->label('University / Organisation (Optional)')
                            ->placeholder('e.g. Rushford Business School or Deloitte')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('country')
                            ->label('Country / Location (Optional)')
                            ->placeholder('e.g. UAE, Saudi Arabia, Qatar')
                            ->maxLength(255),
                    ]),

                Forms\Components\Section::make('Testimonial Quote & Portrait')
                    ->schema([
                        Forms\Components\Textarea::make('testimonial')
                            ->label('Quote / Testimonial')
                            ->required()
                            ->rows(5)
                            ->formatStateUsing(fn (?string $state, ?OurStoryTestimonial $record): string => $record ? $record->plain_quote : (string) ($state ?? ''))
                            ->helperText('Enter the student testimonial quote (plain text — stylized quotation marks are added automatically on the page).')
                            ->columnSpanFull(),
                        Forms\Components\Hidden::make('photo'),
                        MediaPicker::make('media_asset_id')
                            ->label('Student Portrait Photo')
                            ->folder('testimonials')
                            ->urlField('photo')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Page Assignment & Visibility')
                    ->description('Choose one or multiple pages where this testimonial should display. Unselected pages will NOT display this testimonial.')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('pages')
                            ->label('Display on Pages')
                            ->options(fn (): array => TestimonialPages::options())
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->required()
                            ->minItems(1)
                            ->default(fn (): array => TestimonialPages::keys())
                            ->helperText('Select single or multiple pages. This testimonial displays ONLY on selected page(s).')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first in the Voices carousel.'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Inactive testimonials are hidden from all pages.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable()
                    ->width('56px'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Student')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->width('220px')
                    ->extraHeaderAttributes(['style' => 'width: 220px; min-width: 190px; max-width: 220px;'])
                    ->extraAttributes(['style' => 'width: 220px; min-width: 190px; max-width: 220px; white-space: normal;'])
                    ->description(fn (OurStoryTestimonial $record): string => $record->display_role),
                Tables\Columns\TextColumn::make('plain_quote')
                    ->label('Quote')
                    ->limit(220)
                    ->wrap()
                    ->extraHeaderAttributes(['style' => 'min-width: 380px; width: 46%;'])
                    ->extraAttributes(['style' => 'min-width: 380px; width: 46%; white-space: normal;']),
                Tables\Columns\TextColumn::make('pages')
                    ->label('Pages')
                    ->badge()
                    ->color('primary')
                    ->formatStateUsing(fn (string $state): string => TestimonialPages::label($state)),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active')
                    ->width('80px'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('page')
                    ->label('Display Page')
                    ->options(fn (): array => TestimonialPages::options())
                    ->query(function (Builder $query, array $data): Builder {
                        $value = trim((string) ($data['value'] ?? ''));

                        return $value !== ''
                            ? $query->whereJsonContains('pages', $value)
                            : $query;
                    }),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOurStoryTestimonials::route('/'),
            'create' => Pages\CreateOurStoryTestimonial::route('/create'),
            'edit' => Pages\EditOurStoryTestimonial::route('/{record}/edit'),
        ];
    }
}
