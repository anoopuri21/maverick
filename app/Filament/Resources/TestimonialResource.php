<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Filament\Resources\TestimonialResource\RelationManagers;
use App\Models\Testimonial;
use App\Services\CloudinaryService;
use App\Filament\Concerns\HandlesCloudinaryImageFields;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class TestimonialResource extends Resource
{
    use HandlesCloudinaryImageFields;

    protected static ?string $model = Testimonial::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Section::make('Person Details')
                    ->schema([
                        \Filament\Forms\Components\Grid::make(2)->schema([
                            \Filament\Forms\Components\TextInput::make('name')
                                ->required(),
                            \Filament\Forms\Components\TextInput::make('designation')
                                ->helperText('e.g. MBA Student, Director'),
                            \Filament\Forms\Components\TextInput::make('company')
                                ->helperText('e.g. Mumbai, Emirates Airlines, or Category like STUDENT/ALUMNI/CORPORATE'),
                        ]),
                    ]),

                \Filament\Forms\Components\Section::make('Video')
                    ->description('Just paste YouTube link — thumbnail auto-generate ho jayega!')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('video_url')
                            ->label('YouTube Video URL')
                            ->placeholder('https://youtube.com/watch?v=xxxxx OR https://youtu.be/xxxxx')
                            ->helperText('Paste any YouTube URL format. Example: https://www.youtube.com/watch?v=4p0rsCEljgo')
                            ->required()
                            ->url()
                            ->columnSpanFull(),
                    ]),

                \Filament\Forms\Components\Section::make('Custom Thumbnail (Optional)')
                    ->description('Leave empty to auto-generate from YouTube video')
                    ->collapsed()
                    ->schema([
                        FileUpload::make('thumbnail_url')
                            ->label('Custom Thumbnail Image')
                            ->image()
                            ->imageEditor()
                            ->maxSize(2048)
                            ->helperText('Optional: Upload custom thumbnail. YouTube thumbnail will be auto-used if empty.')
                            ->columnSpanFull()
                            ->nullable()
                            ->getUploadedFileUsing(fn (?string $file): ?array => static::existingCloudinaryImage($file))
                            ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                                return app(CloudinaryService::class)
                                    ->uploadImage($file->getRealPath(), 'testimonials');
                            }),
                    ]),

                \Filament\Forms\Components\Section::make('Display Settings')
                    ->schema([
                        \Filament\Forms\Components\Grid::make(2)->schema([
                            \Filament\Forms\Components\TextInput::make('sort_order')
                                ->numeric()
                                ->default(0)
                                ->helperText('Lower number = shown first'),
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
                ImageColumn::make('thumbnail_url')
                    ->label('Thumbnail')
                    ->square()
                    ->size(60)
                    ->defaultImageUrl(function ($record) {
                        return $record?->auto_thumbnail;
                    }),
                \Filament\Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('designation'),
                \Filament\Tables\Columns\TextColumn::make('company')
                    ->badge(),
                \Filament\Tables\Columns\TextColumn::make('video_url')
                    ->label('Video URL')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->video_url),
                \Filament\Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),
                \Filament\Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->defaultSort('sort_order')
            ->filters([])
            ->actions([
                \Filament\Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkActionGroup::make([
                    \Filament\Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
