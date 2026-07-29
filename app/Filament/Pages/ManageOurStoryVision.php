<?php

namespace App\Filament\Pages;

use App\Settings\OurStoryVisionSettings;
use App\Services\CloudinaryService;
use App\Filament\Concerns\HandlesCloudinaryImageFields;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ManageOurStoryVision extends SettingsPage
{
    use HandlesCloudinaryImageFields;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = $this->preserveExistingImageFields($data, app(static::getSettings()));

        return $data;
    }

    protected static ?string $navigationIcon = 'heroicon-o-eye';
    protected static ?string $navigationGroup = 'Our Story Page';
    protected static ?string $navigationLabel = 'Vision for the Future';
    protected static ?int $navigationSort = 6;
    protected static string $settings = OurStoryVisionSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Vision Content')
                ->schema([
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
                    Grid::make(2)->schema([
                        TextInput::make('cta_label')
                            ->label('CTA Button Text'),
                        TextInput::make('cta_url')
                            ->label('CTA Button URL'),
                    ]),
                ]),

            Section::make('Background Image')
                ->schema([
                    FileUpload::make('background_image_url')
                        ->label('Background Image')
                        ->image()
                        ->imageEditor()
                        ->maxSize(5120)
                        ->helperText('Vision section background image')
                        ->columnSpanFull()
                        ->fetchFileInformation(false)
                        ->nullable()
                        ->getUploadedFileUsing(fn (?string $file): ?array => static::existingCloudinaryImage($file))
                        ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                            return app(CloudinaryService::class)
                                ->uploadImage($file->getRealPath(), 'our-story/vision');
                        }),
                ]),
        ]);
    }
}
