<?php

namespace App\Filament\Pages;

use App\Settings\OurStoryTodaySettings;
use App\Services\CloudinaryService;
use App\Filament\Concerns\HandlesCloudinaryImageFields;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ManageOurStoryToday extends SettingsPage
{
    use HandlesCloudinaryImageFields;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = $this->preserveExistingImageFields($data, app(static::getSettings()));

        return $data;
    }

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Our Story Page';
    protected static ?string $navigationLabel = 'What We Do Today';
    protected static ?int $navigationSort = 3;
    protected static string $settings = OurStoryTodaySettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Section Content')
                ->schema([
                    TextInput::make('badge')
                        ->label('Badge Label')
                        ->required(),
                    TextInput::make('heading')
                        ->label('Heading')
                        ->required(),
                    RichEditor::make('description')
                        ->label('Description')
                        ->toolbarButtons([
                            'bold',
                            'italic',
                            'underline',
                            'link',
                            'bulletList',
                            'orderedList',
                            'redo',
                            'undo',
                        ])
                        ->columnSpanFull(),
                ]),

            Section::make('Section Image')
                ->schema([
                    FileUpload::make('image_url')
                        ->label('Section Image')
                        ->image()
                        ->imageEditor()
                        ->maxSize(5120)
                        ->helperText('What We Do Today section image')
                        ->columnSpanFull()
                        ->fetchFileInformation(false)
                        ->nullable()
                        ->getUploadedFileUsing(fn (?string $file): ?array => static::existingCloudinaryImage($file))
                        ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                            return app(CloudinaryService::class)
                                ->uploadImage($file->getRealPath(), 'our-story/today');
                        }),
                ]),
        ]);
    }
}
