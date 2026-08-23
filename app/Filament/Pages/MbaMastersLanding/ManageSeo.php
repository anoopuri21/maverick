<?php

namespace App\Filament\Pages\MbaMastersLanding;

use App\Filament\Concerns\SavesSettingsGroups;
use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Pages\MbaMastersLanding\Concerns\ManagesMbaMastersChunk;
use App\Settings\MbaMastersSeoSettings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;

class ManageSeo extends Page implements HasForms
{
    use InteractsWithForms;
    use ManagesMbaMastersChunk;
    use SavesSettingsGroups;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Landing Pages';

    protected static ?string $navigationLabel = 'MBA Masters — SEO';

    protected static ?int $navigationSort = 6;

    protected static string $view = 'filament.pages.mba-masters-landing.chunk';

    public function mount(): void
    {
        $this->form->fill([
            'seo' => app(MbaMastersSeoSettings::class)->toArray(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->chunkHint('On-page SEO for /online-mba-masters-uae only. Keep title ≤60 and description ≤160 characters.'),
                Section::make('Meta & social')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('seo.meta_title')->label('Meta Title')->maxLength(60),
                            TextInput::make('seo.canonical_url')->label('Canonical URL')->nullable(),
                        ]),
                        Textarea::make('seo.meta_description')->label('Meta Description')->rows(3)->maxLength(160),
                        TextInput::make('seo.meta_keywords')->label('Meta Keywords'),
                        Select::make('seo.robots')->label('Robots')
                            ->options([
                                'index, follow' => 'Index, Follow',
                                'noindex, follow' => 'No Index, Follow',
                                'index, nofollow' => 'Index, No Follow',
                                'noindex, nofollow' => 'No Index, No Follow',
                            ]),
                        TextInput::make('seo.og_title')->label('OG Title'),
                        Textarea::make('seo.og_description')->label('OG Description')->rows(2),
                        TextInput::make('seo.og_image_url')->hidden(),
                        MediaPicker::forField('seo.og_image_url', 'mba-masters-landing/seo')->label('OG Image'),
                        Grid::make(2)->schema([
                            Select::make('seo.og_type')->label('OG Type')
                                ->options(['website' => 'Website', 'article' => 'Article', 'profile' => 'Profile']),
                            Select::make('seo.twitter_card')->label('Twitter Card')
                                ->options(['summary' => 'Summary', 'summary_large_image' => 'Summary Large Image']),
                        ]),
                        TextInput::make('seo.twitter_title')->label('Twitter Title')->maxLength(70),
                        Textarea::make('seo.twitter_description')->label('Twitter Description')->rows(2)->maxLength(200),
                        TextInput::make('seo.twitter_image_url')->hidden(),
                        MediaPicker::forField('seo.twitter_image_url', 'mba-masters-landing/seo')->label('Twitter Image'),
                        Textarea::make('seo.schema_json')->label('Schema.org JSON-LD')->rows(6),
                        Textarea::make('seo.custom_head_scripts')->label('Custom Head Scripts')->rows(4),
                        Textarea::make('seo.custom_body_scripts')->label('Custom Body Scripts')->rows(4),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->getFormStateOrNotify();
        if ($data === null) {
            return;
        }

        $seo = $this->syncImageIfSelected($data['seo'] ?? [], 'og_image_url');
        $seo = $this->syncImageIfSelected($seo, 'twitter_image_url');

        if ($this->saveSettingsGroup(MbaMastersSeoSettings::class, $seo)) {
            $this->notifySaved('SEO');
        }
    }
}
