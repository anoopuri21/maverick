<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\EnsuresSettingsRowsExist;
use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Settings\CsrCommitmentSettings;
use App\Settings\CsrFocusSettings;
use App\Settings\CsrGallerySettings;
use App\Settings\CsrHeroSettings;
use App\Settings\CsrImpactSettings;
use App\Settings\CsrScholarshipSettings;
use App\Settings\CsrSeoSettings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageCsrCommunityImpact extends Page implements HasForms
{
    use HandlesCloudinaryImageFields;
    use EnsuresSettingsRowsExist;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?string $navigationGroup = 'About Section';
    protected static ?string $navigationLabel = 'CSR Community Impact Page';
    protected static ?int $navigationSort = 14;
    protected static string $view = 'filament.pages.manage-csr-community-impact';

    public array $data = [];

    public function mount(): void
    {
        $focus = app(CsrFocusSettings::class)->toArray();
        $focus['items'] = array_values($focus['items'] ?? []);
        foreach ($focus['items'] as &$focusItem) {
            $focusItem['activities'] = array_values(array_map(
                fn ($activity) => is_string($activity) ? ['activity' => $activity] : $activity,
                $focusItem['activities'] ?? []
            ));
        }
        unset($focusItem);

        $gallery = app(CsrGallerySettings::class)->toArray();
        $gallery['items'] = array_values($gallery['items'] ?? []);

        $impact = app(CsrImpactSettings::class)->toArray();
        $impact['items'] = array_values($impact['items'] ?? []);

        $scholarship = app(CsrScholarshipSettings::class)->toArray();
        $scholarship['items'] = array_values(array_map(
            fn ($item) => is_string($item) ? ['item' => $item] : $item,
            $scholarship['items'] ?? []
        ));

        $this->form->fill([
            'hero' => app(CsrHeroSettings::class)->toArray(),
            'commitment' => app(CsrCommitmentSettings::class)->toArray(),
            'focus' => $focus,
            'gallery' => $gallery,
            'impact' => $impact,
            'scholarship' => $scholarship,
            'seo' => app(CsrSeoSettings::class)->toArray(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Tabs::make('CSR Community Impact')
                    ->tabs([
                        Tab::make('Hero')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                TextInput::make('hero.tag')->label('Eyebrow Tag'),
                                TextInput::make('hero.heading_line1')->label('Heading Line 1'),
                                TextInput::make('hero.heading_italic')->label('Heading (Italic)'),
                                RichEditor::make('hero.description')->columnSpanFull(),
                                MediaPicker::forField('hero.background_image', 'csr/hero')
                    ->label('Background Image')
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Commitment')
                            ->icon('heroicon-o-hand-raised')
                            ->schema([
                                TextInput::make('commitment.label')->label('Section Label'),
                                TextInput::make('commitment.heading')->label('Heading'),
                                TextInput::make('commitment.heading_italic')->label('Heading (Italic)'),
                                RichEditor::make('commitment.body')->columnSpanFull(),
                                MediaPicker::forField('commitment.image_url', 'csr/commitment')
                    ->label('Section Image')
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Focus Areas')
                            ->icon('heroicon-o-squares-2x2')
                            ->schema([
                                TextInput::make('focus.label')->label('Section Label'),
                                TextInput::make('focus.heading')->label('Heading'),
                                TextInput::make('focus.heading_italic')->label('Heading (Italic)'),
                                Repeater::make('focus.items')
                                    ->label('Focus Area Cards')
                                    ->schema([
                                        TextInput::make('title'),
                                        TextInput::make('icon')->label('Lucide Icon Name'),
                                        Repeater::make('activities')
                                            ->label('Activities')
                                            ->simple(
                                                TextInput::make('activity'),
                                            )
                    ->reorderable()
                    ->columnSpanFull(),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Gallery')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                TextInput::make('gallery.label')->label('Section Label'),
                                TextInput::make('gallery.heading')->label('Heading'),
                                TextInput::make('gallery.heading_italic')->label('Heading (Italic)'),
                                Repeater::make('gallery.items')
                                    ->label('Gallery Items')
                                    ->schema([
                                        TextInput::make('title'),
                                        RichEditor::make('description')->columnSpanFull(),
                                        MediaPicker::forField('image', 'csr/gallery')
                    ->label('Image')
                    ->columnSpanFull(),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Impact Numbers')
                            ->icon('heroicon-o-chart-bar')
                            ->schema([
                                Repeater::make('impact.items')
                                    ->label('Impact Counters')
                                    ->schema([
                                        Grid::make(3)->schema([
                                            TextInput::make('value')->numeric()->nullable(),
                                            TextInput::make('suffix')->label('Suffix (e.g. +)'),
                                            TextInput::make('label'),
                                        ]),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Scholarships')
                            ->icon('heroicon-o-academic-cap')
                            ->schema([
                                TextInput::make('scholarship.label')->label('Section Label'),
                                TextInput::make('scholarship.heading')->label('Heading'),
                                TextInput::make('scholarship.heading_italic')->label('Heading (Italic)'),
                                RichEditor::make('scholarship.body')->columnSpanFull(),
                                Repeater::make('scholarship.items')
                                    ->label('Checklist Items')
                                    ->simple(
                                        TextInput::make('item'),
                                    )
                    ->reorderable()
                    ->columnSpanFull(),
                            ]),

                        Tab::make('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                TextInput::make('seo.meta_title')->label('Meta Title')->maxLength(60),
                                Textarea::make('seo.meta_description')->label('Meta Description')->rows(3)->maxLength(160),
                                Textarea::make('seo.meta_keywords')->label('Meta Keywords')->rows(2),
                                Grid::make(2)->schema([
                                    TextInput::make('seo.canonical_url')->label('Canonical URL'),
                                    Select::make('seo.robots')->label('Robots')
                                        ->options([
                                            'index, follow' => 'Index, Follow (Default)',
                                            'noindex, follow' => 'No Index, Follow',
                                            'index, nofollow' => 'Index, No Follow',
                                            'noindex, nofollow' => 'No Index, No Follow',
                                        ]),
                                ]),
                                TextInput::make('seo.og_title')->label('OG Title')->maxLength(60),
                                Textarea::make('seo.og_description')->label('OG Description')->rows(3)->maxLength(200),
                                MediaPicker::forField('seo.og_image_url', 'csr/seo')->label('OG Image'),
                                Grid::make(2)->schema([
                                    Select::make('seo.og_type')->label('OG Type')
                                        ->options(['website' => 'Website', 'article' => 'Article', 'profile' => 'Profile']),
                                    Select::make('seo.twitter_card')->label('Twitter Card')
                                        ->options(['summary' => 'Summary', 'summary_large_image' => 'Summary Large Image']),
                                ]),
                                TextInput::make('seo.twitter_title')->label('Twitter Title')->maxLength(70),
                                Textarea::make('seo.twitter_description')->label('Twitter Description')->rows(3)->maxLength(200),
                                MediaPicker::forField('seo.twitter_image_url', 'csr/seo')->label('Twitter Image'),
                                Textarea::make('seo.schema_json')->label('Schema.org JSON-LD')->rows(6),
                                Textarea::make('seo.custom_head_scripts')->label('Custom Head Scripts')->rows(4),
                                Textarea::make('seo.custom_body_scripts')->label('Custom Body Scripts')->rows(4),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

        $hero = $data['hero'] ?? [];
        $hero = MediaPicker::syncFieldFromAsset($hero, 'background_image');

        $commitment = $data['commitment'] ?? [];
        $commitment = MediaPicker::syncFieldFromAsset($commitment, 'image_url');

        $focus = $data['focus'] ?? [];
        foreach ($focus['items'] ?? [] as &$item) {
            if (! is_array($item)) {
                continue;
            }
            $item['activities'] = array_values(array_filter(
                array_map(
                    fn ($activity) => is_array($activity) ? ($activity['activity'] ?? null) : $activity,
                    is_array($item['activities'] ?? null) ? $item['activities'] : []
                )
            ));
        }
        unset($item);
        $focus['items'] = array_values($focus['items'] ?? []);

        $gallery = $data['gallery'] ?? [];
        foreach ($gallery['items'] ?? [] as &$item) {
            if (! is_array($item)) {
                continue;
            }
            $item = MediaPicker::syncFieldFromAsset($item, 'image');
        }
        unset($item);
        $gallery['items'] = array_values($gallery['items'] ?? []);

        $impact = $data['impact'] ?? [];
        $impact['items'] = array_values($impact['items'] ?? []);

        $scholarship = $data['scholarship'] ?? [];
        $scholarship['items'] = array_values(array_filter(
            array_map(
                fn ($item) => is_array($item) ? ($item['item'] ?? null) : $item,
                $scholarship['items'] ?? []
            )
        ));

        $seo = $data['seo'] ?? [];
        $seo = MediaPicker::syncFieldFromAsset($seo, 'og_image_url');
        $seo = MediaPicker::syncFieldFromAsset($seo, 'twitter_image_url');

        $this->saveSettingsGroup(CsrHeroSettings::class, $hero);
        $this->saveSettingsGroup(CsrCommitmentSettings::class, $commitment);
        $this->saveSettingsGroup(CsrFocusSettings::class, $focus);
        $this->saveSettingsGroup(CsrGallerySettings::class, $gallery);
        $this->saveSettingsGroup(CsrImpactSettings::class, $impact);
        $this->saveSettingsGroup(CsrScholarshipSettings::class, $scholarship);
        $this->saveSettingsGroup(CsrSeoSettings::class, $seo);

            Notification::make()
                ->title('CSR Community Impact saved')
                ->success()
                ->send();
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            Notification::make()
                ->title('Could not save CSR Community Impact page')
                ->danger()
                ->send();
        }
    }

    /** @param  class-string  $settingsClass */
    protected function saveSettingsGroup(string $settingsClass, array $payload): void
    {
        $settings = app($settingsClass);
        $payload = $this->ensureAllSettingsProperties($settings, $payload);
        $payload = $this->preserveExistingImageFields($payload, $settings);
        $this->ensureSettingsRowsExist($settings);
        app()->forgetInstance($settingsClass);
        app($settingsClass)->fill($payload)->save();
    }

}
