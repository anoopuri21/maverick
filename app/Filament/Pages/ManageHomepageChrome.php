<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Settings\HomepageChromeSettings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageHomepageChrome extends SettingsPage
{
    use HandlesCloudinaryImageFields;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?string $navigationGroup = 'Homepage';

    protected static ?string $navigationLabel = 'Section Headings';

    protected static ?int $navigationSort = 11;

    protected static string $settings = HomepageChromeSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Featured Programs')->schema([
                TextInput::make('featured_label')->label('Label'),
                Grid::make(2)->schema([
                    TextInput::make('featured_heading_line1')->label('Heading Line 1'),
                    TextInput::make('featured_heading_line2')->label('Heading Line 2 (Accent)'),
                ]),
                TextInput::make('featured_subtitle')->label('Subtitle')->columnSpanFull(),
                TextInput::make('featured_cta_label')->label('Card CTA Label'),
            ])->collapsible(),
            Section::make('Alumni Network')->schema([
                TextInput::make('alumni_label')->label('Label'),
                Grid::make(2)->schema([
                    TextInput::make('alumni_heading')->label('Heading'),
                    TextInput::make('alumni_heading_accent')->label('Accent Word'),
                ]),
                TextInput::make('alumni_subtitle')->label('Subtitle')->columnSpanFull(),
                TextInput::make('alumni_description')->label('Description')->columnSpanFull(),
                TextInput::make('alumni_trust')->label('Trust Line')->columnSpanFull(),
            ])->collapsible(),
            Section::make('Accreditations')->schema([
                TextInput::make('accred_label')->label('Label'),
                Grid::make(2)->schema([
                    TextInput::make('accred_heading_line1')->label('Heading Line 1'),
                    TextInput::make('accred_heading_line2')->label('Heading Line 2 (Accent)'),
                ]),
                TextInput::make('accred_subtitle')->label('Subtitle')->columnSpanFull(),
                TextInput::make('accred_trust')->label('Trust Line')->columnSpanFull(),
            ])->collapsible(),
            Section::make('Faculty Insights')->schema([
                TextInput::make('faculty_label')->label('Label'),
                Grid::make(2)->schema([
                    TextInput::make('faculty_heading_line1')->label('Heading Line 1'),
                    TextInput::make('faculty_heading_line2')->label('Heading Line 2 (Accent)'),
                ]),
                TextInput::make('faculty_subtitle')->label('Subtitle')->columnSpanFull(),
            ])->collapsible(),
            Section::make('Upcoming Events')->schema([
                TextInput::make('events_label')->label('Label'),
                Grid::make(2)->schema([
                    TextInput::make('events_heading_line1')->label('Heading Line 1'),
                    TextInput::make('events_heading_line2')->label('Heading Line 2'),
                ]),
                TextInput::make('events_subtitle')->label('Subtitle')->columnSpanFull(),
            ])->collapsible(),
            Section::make('Video Testimonials')->schema([
                TextInput::make('testimonials_label')->label('Label'),
                Grid::make(2)->schema([
                    TextInput::make('testimonials_heading_line1')->label('Heading Line 1'),
                    TextInput::make('testimonials_heading_line2')->label('Heading Line 2 (Accent)'),
                ]),
                TextInput::make('testimonials_subtitle')->label('Subtitle')->columnSpanFull(),
            ])->collapsible(),
            Section::make('FAQ')->schema([
                TextInput::make('faq_label')->label('Label'),
                Grid::make(2)->schema([
                    TextInput::make('faq_heading_line1')->label('Heading Line 1'),
                    TextInput::make('faq_heading_line2')->label('Heading Line 2 (Accent)'),
                ]),
                TextInput::make('faq_subtitle')->label('Subtitle')->columnSpanFull(),
                MediaPicker::forField('faq_image_url', 'homepage/faq')
                    ->label('FAQ Image')
                    ->columnSpanFull(),
            ])->collapsible(),
        ]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = MediaPicker::syncFieldFromAsset($data, 'faq_image_url');

        return $this->preserveExistingImageFields($data, app(static::$settings));
    }
}
