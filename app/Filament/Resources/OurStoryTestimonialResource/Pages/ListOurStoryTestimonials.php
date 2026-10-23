<?php

declare(strict_types=1);

namespace App\Filament\Resources\OurStoryTestimonialResource\Pages;

use App\Filament\Resources\OurStoryTestimonialResource;
use App\Settings\VoicesTestimonialsSettings;
use App\Support\PublicContentCache;
use App\Support\TestimonialPages;
use Filament\Actions;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListOurStoryTestimonials extends ListRecords
{
    protected static string $resource = OurStoryTestimonialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('sectionSettings')
                ->label('Section & Pages Settings')
                ->icon('heroicon-o-cog-6-tooth')
                ->color('gray')
                ->modalHeading('Testimonials Section & Display Pages Settings')
                ->modalDescription('Manage global default copy, visibility, and available pages for the shared Voices Testimonials component.')
                ->modalWidth('2xl')
                ->fillForm(static function (): array {
                    $settings = safe_settings(VoicesTestimonialsSettings::class);

                    return [
                        'show_section' => (bool) ($settings->show_section ?? true),
                        'label' => $settings->label,
                        'heading' => $settings->heading,
                        'intro' => $settings->intro,
                        'stack_caption' => $settings->stack_caption,
                        'pages' => settings_array($settings->pages ?? []),
                    ];
                })
                ->form([
                    Section::make('Global Section Visibility & Copy Defaults')
                        ->columns(2)
                        ->schema([
                            Toggle::make('show_section')
                                ->label('Enable Testimonials Section Globally')
                                ->default(true)
                                ->columnSpanFull(),
                            TextInput::make('label')
                                ->label('Default Eyebrow Label')
                                ->maxLength(120),
                            TextInput::make('stack_caption')
                                ->label('Default Portrait Overlay Caption')
                                ->maxLength(160),
                            TextInput::make('heading')
                                ->label('Default Section Heading')
                                ->maxLength(255)
                                ->columnSpanFull(),
                            Textarea::make('intro')
                                ->label('Default Section Intro')
                                ->rows(2)
                                ->maxLength(600)
                                ->columnSpanFull(),
                        ]),

                    Section::make('Available Display Pages (Dropdown Options)')
                        ->description('Add any new page key here when placing `<x-voices-testimonials page="page-key" />` on a page — it appears immediately in every testimonial\'s "Display on Pages" dropdown.')
                        ->schema([
                            Repeater::make('pages')
                                ->label('Registered Pages')
                                ->columns(2)
                                ->defaultItems(0)
                                ->reorderable()
                                ->addActionLabel('Add Display Page')
                                ->schema([
                                    TextInput::make('key')
                                        ->label('Page Key (slug)')
                                        ->placeholder('e.g. dual-mba')
                                        ->required()
                                        ->maxLength(100),
                                    TextInput::make('label')
                                        ->label('Admin Dropdown Label')
                                        ->placeholder('e.g. Dual MBA Page (/dual-mba-online)')
                                        ->required()
                                        ->maxLength(160),
                                ]),
                        ]),
                ])
                ->action(static function (array $data): void {
                    $settings = app(VoicesTestimonialsSettings::class);
                    $settings->show_section = (bool) ($data['show_section'] ?? true);
                    $settings->label = isset($data['label']) ? trim((string) $data['label']) : null;
                    $settings->heading = isset($data['heading']) ? trim((string) $data['heading']) : null;
                    $settings->intro = isset($data['intro']) ? trim((string) $data['intro']) : null;
                    $settings->stack_caption = isset($data['stack_caption']) ? trim((string) $data['stack_caption']) : null;

                    $cleanPages = [];
                    $seenKeys = [];
                    foreach (($data['pages'] ?? []) as $row) {
                        if (! is_array($row)) {
                            continue;
                        }
                        $key = TestimonialPages::sanitizeKey($row['key'] ?? '');
                        $label = trim((string) ($row['label'] ?? ''));
                        if ($key !== '' && $label !== '' && ! isset($seenKeys[$key])) {
                            $seenKeys[$key] = true;
                            $cleanPages[] = [
                                'key' => $key,
                                'label' => $label,
                            ];
                        }
                    }

                    $settings->pages = array_values($cleanPages);
                    $settings->save();

                    PublicContentCache::flush();

                    Notification::make()
                        ->title('Testimonials section & page settings saved')
                        ->success()
                        ->send();
                }),

            Actions\CreateAction::make()
                ->label('New Testimonial'),
        ];
    }
}
