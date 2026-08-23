<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\StudentSuccessVideoResource\Pages;
use App\Models\StudentSuccessVideo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class StudentSuccessVideoResource extends Resource
{
    use HandlesCloudinaryImageFields;

    protected static ?string $model = StudentSuccessVideo::class;

    protected static ?string $navigationIcon = 'heroicon-o-play-circle';

    protected static ?string $navigationGroup = 'Insights';

    protected static ?string $navigationLabel = 'Video Success Stories';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->maxLength(255),
                Forms\Components\TextInput::make('role')->maxLength(255),
                Forms\Components\TextInput::make('youtube_url')
                    ->label('YouTube URL')
                    ->placeholder('https://www.youtube.com/watch?v=xxxxx or https://youtu.be/xxxxx')
                    ->helperText('Paste any YouTube URL. Thumbnail is auto-picked if you do not upload one.')
                    ->live()
                    ->nullable()
                    ->columnSpanFull(),
                Forms\Components\Placeholder::make('youtube_thumb_preview')
                    ->label('Thumbnail preview')
                    ->content(function (Get $get): HtmlString {
                        $src = youtube_thumbnail_url($get('youtube_url'), $get('thumbnail_url'));
                        if (! $src) {
                            return new HtmlString('<p style="color:#6b7280;font-size:13px;margin:0">Paste a YouTube URL to auto-load the thumbnail, or upload a custom image.</p>');
                        }
                        $fallback = youtube_thumbnail_fallback($get('youtube_url'));
                        $onerror = $fallback && $fallback !== $src
                            ? ' onerror="if(this.dataset.retry){this.src=this.dataset.retry;delete this.dataset.retry;}" data-retry="'.e($fallback).'"'
                            : '';

                        return new HtmlString('<img src="'.e($src).'" alt="YouTube thumbnail preview" style="max-width:320px;width:100%;border-radius:12px;display:block"'.$onerror.'>');
                    }),
                MediaPicker::forField('thumbnail_url', 'student-success/videos')
                    ->label('Custom Thumbnail (optional)')
                    ->helperText('Leave empty to use the YouTube thumbnail.'),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->helperText('Lower number = shown first'),
                Forms\Components\Toggle::make('is_active')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
                Tables\Columns\ImageColumn::make('thumbnail_url')
                    ->label('Thumbnail')
                    ->size(72)
                    ->defaultImageUrl(fn ($record) => $record?->auto_thumbnail),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('role')->searchable(),
                Tables\Columns\TextColumn::make('youtube_url')->limit(40)->toggleable(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only')
                    ->placeholder('All'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudentSuccessVideos::route('/'),
            'create' => Pages\CreateStudentSuccessVideo::route('/create'),
            'edit' => Pages\EditStudentSuccessVideo::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
