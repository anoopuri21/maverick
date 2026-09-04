<?php

namespace App\Filament\Pages\MbaMastersLanding;

use App\Filament\Concerns\SavesSettingsGroups;
use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Pages\MbaMastersLanding\Concerns\ManagesMbaMastersChunk;
use App\Settings\MbaMastersHeroSettings;
use App\Settings\MbaMastersTrustSettings;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;

class ManageHeroTrust extends Page implements HasForms
{
    use InteractsWithForms;
    use ManagesMbaMastersChunk;
    use SavesSettingsGroups;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Landing Pages';

    protected static ?string $navigationLabel = 'MBA Masters — Hero & Trust';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.mba-masters-landing.chunk';

    public function mount(): void
    {
        $trust = app(MbaMastersTrustSettings::class)->toArray();
        $trust['stats'] = array_values($trust['stats'] ?? []);

        $this->form->fill([
            'hero' => app(MbaMastersHeroSettings::class)->toArray(),
            'trust' => $trust,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->chunkHint('Edits only the hero enquiry + trust proof strip on /online-mba-masters-uae.'),
                Section::make('Hero')
                    ->description('Above-the-fold headline, background, CTAs and enquiry form title.')
                    ->schema([
                        TextInput::make('hero.eyebrow')->label('Eyebrow'),
                        TextInput::make('hero.headline')->label('H1 headline')->columnSpanFull(),
                        Textarea::make('hero.subheading')->label('Subheading')->rows(3)->columnSpanFull(),
                        TextInput::make('hero.background_image')->hidden(),
                        MediaPicker::forField('hero.background_image', 'mba-masters-landing')
                            ->label('Background image'),
                        TextInput::make('hero.form_title')->label('Form title'),
                        TextInput::make('hero.cta_primary_label')->label('Primary CTA label'),
                        TextInput::make('hero.cta_primary_url')->label('Primary CTA URL'),
                        TextInput::make('hero.cta_secondary_label')->label('Secondary CTA label'),
                        TextInput::make('hero.cta_secondary_url')->label('Secondary CTA URL'),
                        TextInput::make('hero.cta_tertiary_label')->label('Tertiary CTA label'),
                        TextInput::make('hero.cta_tertiary_url')->label('Tertiary CTA URL'),
                    ])
                    ->columns(2)
                    ->collapsible(),
                Section::make('Trust stats')
                    ->schema([
                                TextInput::make('trust.label')->label('Section heading')->columnSpanFull(),
                                Textarea::make('trust.quote')->label('Trust quote')->rows(2)->columnSpanFull(),
                                Repeater::make('trust.stats')
                            ->schema([
                                TextInput::make('value')->label('Value')->placeholder('4500+'),
                                TextInput::make('label')->label('Label'),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? $state['value'] ?? null)
                            ->addActionLabel('Add stat')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->getFormStateOrNotify();
        if ($data === null) {
            return;
        }

        $hero = $this->syncImageIfSelected($data['hero'] ?? [], 'background_image');
        $trust = $data['trust'] ?? [];
        $trust['stats'] = array_values($trust['stats'] ?? []);

        $ok = $this->saveSettingsGroup(MbaMastersHeroSettings::class, $hero)
            && $this->saveSettingsGroup(MbaMastersTrustSettings::class, $trust);

        if ($ok) {
            $this->notifySaved('Hero & Trust');
        }
    }
}
