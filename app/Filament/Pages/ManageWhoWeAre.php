<?php

namespace App\Filament\Pages;

use App\Settings\WhoWeAreSettings;
use App\Services\CloudinaryService;
use App\Filament\Concerns\HandlesCloudinaryImageFields;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ManageWhoWeAre extends SettingsPage
{
    use HandlesCloudinaryImageFields;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = $this->preserveExistingImageFields($data, app(static::getSettings()));

        return $data;
    }

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Homepage';
    protected static ?string $navigationLabel = 'Who We Are';
    protected static ?int $navigationSort = 3;
    protected static string $settings = WhoWeAreSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Heading')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('heading_line1')->required(),
                        TextInput::make('heading_line2')
                            ->label('Line 2 (Red/Accent)')
                            ->required(),
                    ]),
                    TextInput::make('body_text')
                        ->label('Body Text')
                        ->columnSpanFull(),
                    TextInput::make('cta_text')->label('CTA Button Text'),
                    TextInput::make('cta_url')->label('CTA Button URL'),
                ]),

            Section::make('Stats')
                ->columns(2)
                ->schema([
                    TextInput::make('stat1_value')->label('Stat 1 Value'),
                    TextInput::make('stat1_suffix')->label('Stat 1 Suffix'),
                    TextInput::make('stat1_label')->label('Stat 1 Label'),
                    TextInput::make('stat2_value')->label('Stat 2 Value'),
                    TextInput::make('stat2_suffix')->label('Stat 2 Suffix'),
                    TextInput::make('stat2_label')->label('Stat 2 Label'),
                ]),

            Section::make('Image')
                ->schema([
                    FileUpload::make('image_url')
                        ->label('Section Image')
                        ->image()
                        ->imageEditor()
                        ->maxSize(5120)
                        ->helperText('Who We Are section image')
                        ->columnSpanFull()
                        ->fetchFileInformation(false)
                        ->nullable()
                        ->getUploadedFileUsing(fn (?string $file): ?array => static::existingCloudinaryImage($file))
                        ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                            return app(CloudinaryService::class)
                                ->uploadImage($file->getRealPath(), 'homepage/who-we-are');
                        }),
                ]),
        ]);
    }
}