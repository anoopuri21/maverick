<?php

namespace App\Filament\Pages;

use App\Settings\OurStoryHeroSettings;
use App\Services\CloudinaryService;
use App\Filament\Concerns\HandlesCloudinaryImageFields;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ManageOurStoryHero extends SettingsPage
{
    use HandlesCloudinaryImageFields;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = $this->preserveExistingImageFields($data, app(static::getSettings()));

        return $data;
    }

    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationGroup = 'Our Story Page';
    protected static ?string $navigationLabel = 'Hero Section';
    protected static ?int $navigationSort = 1;

    protected static bool $shouldRegisterNavigation = false;
    protected static string $settings = OurStoryHeroSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Hero Content')
                ->schema([
                    TextInput::make('heading')
                        ->columnSpanFull(),
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

            Section::make('Hero Image')
                ->schema([
                    FileUpload::make('image_url')
                        ->label('Hero Image')
                        ->image()
                        ->imageEditor()
                        ->maxSize(5120)
                        ->helperText('Hero section image')
                        ->columnSpanFull()
                        ->fetchFileInformation(false)
                        ->nullable()
                        ->getUploadedFileUsing(fn (?string $file): ?array => static::existingCloudinaryImage($file))
                        ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                            return cloudinary_upload($file->getRealPath() ?: null, 'our-story/hero');
                        }),
                ]),
        ]);
    }
}
