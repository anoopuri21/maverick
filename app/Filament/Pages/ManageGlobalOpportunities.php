<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Concerns\HydratesRepeaterMediaFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Support\RepeaterNormalizer;
use App\Settings\GlobalOpportunitiesSettings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageGlobalOpportunities extends SettingsPage
{
    use HandlesCloudinaryImageFields;
    use HydratesRepeaterMediaFields;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationGroup = 'Global Pathways';
    protected static ?string $navigationLabel = 'Global Opportunities';
    protected static ?int $navigationSort = 1;
    protected static string $settings = GlobalOpportunitiesSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Section Heading')->schema([
                TextInput::make('label')->label('Section Label'),
                TextInput::make('coming_soon_label')->label('Coming Soon Badge'),
                TextInput::make('heading')->columnSpanFull(),
                Textarea::make('subtitle')->rows(2)->columnSpanFull(),
            ]),

            Section::make('Global Opportunities Items')->schema([
                TextInput::make('left_title')->label('Column Title')->columnSpanFull(),
                Repeater::make('opportunities')
                    ->label('Opportunity Items')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title'),
                            TextInput::make('slug')
                                ->label('Slug')
                                ->prefix(rtrim(url('/'), '/').'/')
                                ->helperText('Page slug only, e.g. dual-mba-online. This becomes the actual URL of the page.')
                                ->rule('regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
                                ->validationMessages([
                                    'regex' => 'Only lowercase letters, numbers and hyphens. No slashes or spaces.',
                                ])
                                ->dehydrateStateUsing(fn (?string $state): ?string => filled($state)
                                    ? \Illuminate\Support\Str::slug(trim($state, '/'))
                                    : null)
                                ->disabled(fn (Get $get): bool => (bool) $get('coming_soon'))
                                ->dehydrated(),
                        ]),
                        Toggle::make('coming_soon')
                            ->label('Coming Soon')
                            ->helperText('Slug is kept but the card will not link until Coming Soon is turned off.')
                            ->default(false)
                            ->live(),
                        RichEditor::make('desc')->label('Description')->columnSpanFull(),
                        MediaPicker::forField('image', 'global-opportunities')
                    ->label('Image')
                    ->helperText('Upload from library (priority) or use the URL field below.')
                    ->columnSpanFull(),
                        TextInput::make('image_url')->label('Image URL (fallback)')
                            ->helperText('Used only if no image is uploaded.'),
                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => ($state['title'] ?? null) . (! empty($state['coming_soon']) ? ' — Coming Soon' : ''))
                    ->columnSpanFull(),
            ]),

            Section::make('Global Pathways Items')->schema([
                TextInput::make('right_title')->label('Column Title')->columnSpanFull(),
                Repeater::make('pathways')
                    ->label('Pathway Items')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title'),
                            TextInput::make('slug')
                                ->label('Slug')
                                ->prefix(rtrim(url('/'), '/').'/')
                                ->helperText('Page slug only, e.g. dual-mba-online. This becomes the actual URL of the page.')
                                ->rule('regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
                                ->validationMessages([
                                    'regex' => 'Only lowercase letters, numbers and hyphens. No slashes or spaces.',
                                ])
                                ->dehydrateStateUsing(fn (?string $state): ?string => filled($state)
                                    ? \Illuminate\Support\Str::slug(trim($state, '/'))
                                    : null)
                                ->disabled(fn (Get $get): bool => (bool) $get('coming_soon'))
                                ->dehydrated(),
                        ]),
                        Toggle::make('coming_soon')
                            ->label('Coming Soon')
                            ->helperText('Slug is kept but the card will not link until Coming Soon is turned off.')
                            ->default(false)
                            ->live(),
                        RichEditor::make('desc')->label('Description')->columnSpanFull(),
                        MediaPicker::forField('image', 'global-pathways')
                    ->label('Image')
                    ->helperText('Upload from library (priority) or use the URL field below.')
                    ->columnSpanFull(),
                        TextInput::make('image_url')->label('Image URL (fallback)')
                            ->helperText('Used only if no image is uploaded.'),
                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => ($state['title'] ?? null) . (! empty($state['coming_soon']) ? ' — Coming Soon' : ''))
                    ->columnSpanFull(),
            ]),
        ]);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['opportunities'] = $this->hydrateRepeaterMediaFields($data['opportunities'] ?? [], 'image');
        $data['pathways'] = $this->hydrateRepeaterMediaFields($data['pathways'] ?? [], 'image');

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $settings = app(static::$settings);

        foreach ($data['opportunities'] ?? [] as $index => $item) {
            if (! is_array($item)) {
                continue;
            }

            $item = $this->syncImageIfSelected($item, 'image');
            if (filled($item['slug'] ?? null)) {
                $item['slug'] = \Illuminate\Support\Str::slug(trim((string) $item['slug'], '/'));
            }
            $existing = $settings->opportunities[$index] ?? [];
            $data['opportunities'][$index] = $this->preserveRepeaterImageFields(
                [$item],
                is_array($existing) ? [$existing] : [],
                'image'
            )[0] ?? $item;
        }

        foreach ($data['pathways'] ?? [] as $index => $item) {
            if (! is_array($item)) {
                continue;
            }

            $item = $this->syncImageIfSelected($item, 'image');
            if (filled($item['slug'] ?? null)) {
                $item['slug'] = \Illuminate\Support\Str::slug(trim((string) $item['slug'], '/'));
            }
            $existing = $settings->pathways[$index] ?? [];
            $data['pathways'][$index] = $this->preserveRepeaterImageFields(
                [$item],
                is_array($existing) ? [$existing] : [],
                'image'
            )[0] ?? $item;
        }

        $data['opportunities'] = RepeaterNormalizer::stripEmptyRows($data['opportunities'] ?? []);
        $data['pathways'] = RepeaterNormalizer::stripEmptyRows($data['pathways'] ?? []);

        return $this->preserveExistingImageFields($data, $settings);
    }
}
