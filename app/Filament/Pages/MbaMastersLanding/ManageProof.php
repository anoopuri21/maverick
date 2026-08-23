<?php

namespace App\Filament\Pages\MbaMastersLanding;

use App\Filament\Concerns\SavesSettingsGroups;
use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Pages\MbaMastersLanding\Concerns\ManagesMbaMastersChunk;
use App\Settings\MbaMastersAlumniSettings;
use App\Settings\MbaMastersCompareSettings;
use App\Settings\MbaMastersPartnersSettings;
use App\Settings\MbaMastersTestimonialsSettings;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;

class ManageProof extends Page implements HasForms
{
    use InteractsWithForms;
    use ManagesMbaMastersChunk;
    use SavesSettingsGroups;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Landing Pages';

    protected static ?string $navigationLabel = 'MBA Masters — Proof';

    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.pages.mba-masters-landing.chunk';

    public function mount(): void
    {
        $testimonials = app(MbaMastersTestimonialsSettings::class)->toArray();
        $testimonials['items'] = array_values($testimonials['items'] ?? []);

        $compare = app(MbaMastersCompareSettings::class)->toArray();
        $compare['rows'] = array_values($compare['rows'] ?? []);

        $this->form->fill([
            'alumni' => app(MbaMastersAlumniSettings::class)->toArray(),
            'partners' => app(MbaMastersPartnersSettings::class)->toArray(),
            'testimonials' => $testimonials,
            'compare' => $compare,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->chunkHint('Edits alumni, university partners, testimonials and comparison only.'),
                Section::make('Alumni / companies')
                    ->schema([
                        TextInput::make('alumni.label')->label('Section label'),
                        TextInput::make('alumni.heading')->label('Heading')->columnSpanFull(),
                        Textarea::make('alumni.intro')->label('Intro')->rows(2)->columnSpanFull(),
                        Textarea::make('alumni.trust_line')->label('Trust line')->rows(2)->columnSpanFull()
                            ->helperText('Employer logos: Partner Logos admin (type = alumni). Not edited here.'),
                    ])
                    ->columns(2)
                    ->collapsed()
                    ->collapsible(),
                Section::make('University partners')
                    ->schema([
                        TextInput::make('partners.label')->label('Section label'),
                        TextInput::make('partners.heading')->label('Heading')->columnSpanFull(),
                        Textarea::make('partners.intro')->label('Intro')->rows(2)->columnSpanFull(),
                        Textarea::make('partners.trust_line')->label('Trust line')->rows(2)->columnSpanFull()
                            ->helperText('Logos: University Partners admin (active + logo). No duplicate logo CRUD here.'),
                    ])
                    ->columns(2)
                    ->collapsed()
                    ->collapsible(),
                Section::make('Testimonials')
                    ->schema([
                        TextInput::make('testimonials.label')->label('Section label'),
                        TextInput::make('testimonials.heading')->label('Heading')->columnSpanFull(),
                        Textarea::make('testimonials.intro')->label('Intro')->rows(2)->columnSpanFull()
                            ->helperText('Active Our Story testimonials preferred when present; items below are landing placeholders.'),
                        Repeater::make('testimonials.items')
                            ->label('Placeholder quotes')
                            ->schema([
                                TextInput::make('name')->label('Name'),
                                TextInput::make('role')->label('Role / company'),
                                Textarea::make('quote')->label('Quote')->rows(3)->columnSpanFull(),
                                TextInput::make('photo')->hidden(),
                                MediaPicker::forField('photo', 'mba-masters-landing/testimonials')
                                    ->label('Photo')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->addActionLabel('Add placeholder')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsed()
                    ->collapsible(),
                Section::make('Online vs traditional')
                    ->schema([
                        TextInput::make('compare.label')->label('Section label'),
                        TextInput::make('compare.heading')->label('Heading')->columnSpanFull(),
                        Textarea::make('compare.intro')->label('Intro')->rows(2)->columnSpanFull(),
                        TextInput::make('compare.col_online')->label('Online column label'),
                        TextInput::make('compare.col_traditional')->label('Traditional column label'),
                        Repeater::make('compare.rows')
                            ->label('Comparison rows')
                            ->schema([
                                TextInput::make('criterion')->label('Criterion')->required(),
                                $this->richEditor('online', 'Online'),
                                $this->richEditor('traditional', 'Traditional'),
                            ])
                            ->columns(1)
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['criterion'] ?? null)
                            ->addActionLabel('Add row')
                            ->columnSpanFull(),
                        TextInput::make('compare.cta_label')->label('CTA label'),
                        TextInput::make('compare.cta_url')->label('CTA URL'),
                    ])
                    ->columns(2)
                    ->collapsed()
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

        $testimonials = $data['testimonials'] ?? [];
        foreach ($testimonials['items'] ?? [] as &$item) {
            $item = $this->syncImageIfSelected($item, 'photo');
        }
        unset($item);
        $testimonials['items'] = array_values($testimonials['items'] ?? []);

        $compare = $data['compare'] ?? [];
        $compare['rows'] = array_values($compare['rows'] ?? []);

        $ok = $this->saveSettingsGroup(MbaMastersAlumniSettings::class, $data['alumni'] ?? [])
            && $this->saveSettingsGroup(MbaMastersPartnersSettings::class, $data['partners'] ?? [])
            && $this->saveSettingsGroup(MbaMastersTestimonialsSettings::class, $testimonials)
            && $this->saveSettingsGroup(MbaMastersCompareSettings::class, $compare);

        if ($ok) {
            $this->notifySaved('Proof');
        }
    }
}
