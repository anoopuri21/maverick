<?php

namespace App\Filament\Pages\MbaMastersLanding;

use App\Filament\Concerns\SavesSettingsGroups;
use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Pages\MbaMastersLanding\Concerns\ManagesMbaMastersChunk;
use App\Settings\MbaMastersFeesSettings;
use App\Settings\MbaMastersJourneySettings;
use App\Settings\MbaMastersMastersSettings;
use App\Settings\MbaMastersMbaSettings;
use App\Settings\MbaMastersOverviewSettings;
use App\Settings\MbaMastersWhySettings;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;

class ManagePrograms extends Page implements HasForms
{
    use InteractsWithForms;
    use ManagesMbaMastersChunk;
    use SavesSettingsGroups;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Landing Pages';

    protected static ?string $navigationLabel = 'MBA Masters — Programs';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.mba-masters-landing.chunk';

    public function mount(): void
    {
        $overview = app(MbaMastersOverviewSettings::class)->toArray();
        $overview['items'] = array_values($overview['items'] ?? []);

        $why = app(MbaMastersWhySettings::class)->toArray();
        $why['chapters'] = array_values($why['chapters'] ?? []);

        $journey = app(MbaMastersJourneySettings::class)->toArray();
        $journey['steps'] = array_values($journey['steps'] ?? []);

        $mba = app(MbaMastersMbaSettings::class)->toArray();
        $mba['tabs'] = array_values($mba['tabs'] ?? []);
        foreach ($mba['tabs'] as &$tab) {
            $tab['universities'] = array_values($tab['universities'] ?? []);
            $tab['universities'] = $this->hydrateRepeaterMediaFields($tab['universities'], 'logo');
            $tab['universities'] = $this->hydrateRepeaterMediaFields($tab['universities'], 'image');
            foreach ($tab['universities'] as &$uni) {
                $uni['programs'] = array_values($uni['programs'] ?? []);
            }
            unset($uni);
        }
        unset($tab);

        $masters = app(MbaMastersMastersSettings::class)->toArray();
        $masters['universities'] = array_values($masters['universities'] ?? []);
        $masters['universities'] = $this->hydrateRepeaterMediaFields($masters['universities'], 'logo');
        $masters['universities'] = $this->hydrateRepeaterMediaFields($masters['universities'], 'image');
        foreach ($masters['universities'] as &$uni) {
            $uni['programs'] = array_values($uni['programs'] ?? []);
        }
        unset($uni);

        $fees = app(MbaMastersFeesSettings::class)->toArray();
        $fees['rows'] = array_values($fees['rows'] ?? []);

        $this->form->fill([
            'overview' => $overview,
            'why' => $why,
            'journey' => $journey,
            'mba' => $mba,
            'masters' => $masters,
            'fees' => $fees,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->chunkHint('Edits overview, why, admission journey, MBA/Master’s catalogs and fees only.'),
                Section::make('Program overview')
                    ->schema([
                        TextInput::make('overview.label')->label('Section label'),
                        TextInput::make('overview.heading')->label('Heading')->columnSpanFull(),
                        Textarea::make('overview.intro')->label('Intro')->rows(2)->columnSpanFull(),
                        TextInput::make('overview.plate_image')->hidden(),
                        MediaPicker::forField('overview.plate_image', 'mba-masters-landing/overview')
                            ->label('Editorial plate image')
                            ->columnSpanFull(),
                        Repeater::make('overview.items')
                            ->schema([
                                TextInput::make('title')->label('Title')->required(),
                                $this->richEditor('text', 'Text'),
                            ])
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->addActionLabel('Add rail')
                            ->columnSpanFull(),
                        TextInput::make('overview.cta_primary_label')->label('Primary CTA label'),
                        TextInput::make('overview.cta_primary_url')->label('Primary CTA URL'),
                        TextInput::make('overview.cta_secondary_label')->label('Secondary CTA label'),
                        TextInput::make('overview.cta_secondary_url')->label('Secondary CTA URL'),
                    ])
                    ->columns(2)
                    ->collapsed()
                    ->collapsible(),
                Section::make('Why choose Maverick')
                    ->schema([
                        TextInput::make('why.label')->label('Section label'),
                        TextInput::make('why.heading')->label('Heading')->columnSpanFull(),
                        Textarea::make('why.intro')->label('Intro')->rows(2)->columnSpanFull(),
                        Repeater::make('why.chapters')
                            ->schema([
                                TextInput::make('title')->label('Title')->required(),
                                $this->richEditor('text', 'Text'),
                                TextInput::make('anchor')->label('Optional link (#mlp-mba)')->placeholder('#mlp-enquire'),
                            ])
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->addActionLabel('Add chapter')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsed()
                    ->collapsible(),
                Section::make('Admission journey')
                    ->schema([
                        TextInput::make('journey.label')->label('Section label'),
                        TextInput::make('journey.heading')->label('Heading')->columnSpanFull(),
                        Textarea::make('journey.intro')->label('Intro')->rows(2)->columnSpanFull(),
                        Repeater::make('journey.steps')
                            ->schema([
                                TextInput::make('title')->label('Title')->required(),
                                $this->richEditor('text', 'Text'),
                            ])
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->addActionLabel('Add step')
                            ->columnSpanFull(),
                        TextInput::make('journey.cta_label')->label('CTA label'),
                        TextInput::make('journey.cta_url')->label('CTA URL'),
                    ])
                    ->columns(2)
                    ->collapsed()
                    ->collapsible(),
                Section::make('MBA specializations')
                    ->schema([
                        TextInput::make('mba.label')->label('Section label'),
                        TextInput::make('mba.heading')->label('Heading')->columnSpanFull(),
                        Textarea::make('mba.intro')->label('Intro')->rows(2)->columnSpanFull(),
                        TextInput::make('mba.stage_image')->hidden(),
                        MediaPicker::forField('mba.stage_image', 'mba-masters-landing/mba')
                            ->label('Section stage image')
                            ->columnSpanFull(),
                        Repeater::make('mba.tabs')
                            ->label('Tabs')
                            ->schema([
                                TextInput::make('key')->label('Key')->placeholder('general')->required(),
                                TextInput::make('label')->label('Tab label')->required(),
                                Repeater::make('universities')
                                    ->schema([
                                        TextInput::make('name')->label('University')->required()->columnSpanFull(),
                                        Hidden::make('specification'),
                                        TextInput::make('logo')->hidden(),
                                        MediaPicker::forField('logo', 'mba-masters-landing/mba/logos')->label('Logo'),
                                        TextInput::make('image')->hidden(),
                                        MediaPicker::forField('image', 'mba-masters-landing/mba/campuses')->label('Campus / plate image'),
                                        Repeater::make('programs')
                                            ->schema([
                                                TextInput::make('title')->label('Program title')->required(),
                                            ])
                                            ->defaultItems(0)
                                            ->reorderable()
                                            ->addActionLabel('Add program')
                                            ->columnSpanFull(),
                                    ])
                                    ->defaultItems(0)
                                    ->reorderable()
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                                    ->addActionLabel('Add university')
                                    ->columnSpanFull(),
                            ])
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                            ->addActionLabel('Add tab')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsed()
                    ->collapsible(),
                Section::make('Master\'s programs')
                    ->schema([
                        TextInput::make('masters.label')->label('Section label'),
                        TextInput::make('masters.heading')->label('Heading')->columnSpanFull(),
                        Textarea::make('masters.intro')->label('Intro')->rows(2)->columnSpanFull(),
                        TextInput::make('masters.stage_image')->hidden(),
                        MediaPicker::forField('masters.stage_image', 'mba-masters-landing/masters')
                            ->label('Section stage image')
                            ->columnSpanFull(),
                        Repeater::make('masters.universities')
                            ->label('Universities')
                            ->schema([
                                TextInput::make('name')->label('University')->required()->columnSpanFull(),
                                Hidden::make('specification'),
                                TextInput::make('logo')->hidden(),
                                MediaPicker::forField('logo', 'mba-masters-landing/masters/logos')->label('Logo'),
                                TextInput::make('image')->hidden(),
                                MediaPicker::forField('image', 'mba-masters-landing/masters/campuses')->label('Campus / plate image'),
                                Repeater::make('programs')
                                    ->schema([
                                        TextInput::make('title')->label('Program title')->required(),
                                    ])
                                    ->defaultItems(0)
                                    ->reorderable()
                                    ->addActionLabel('Add program')
                                    ->columnSpanFull(),
                            ])
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->addActionLabel('Add university')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsed()
                    ->collapsible(),
                Section::make('Fees & payment')
                    ->schema([
                        TextInput::make('fees.label')->label('Section label'),
                        TextInput::make('fees.heading')->label('Heading')->columnSpanFull(),
                        Textarea::make('fees.intro')->label('Intro')->rows(2)->columnSpanFull(),
                        Textarea::make('fees.note')->label('Footnote / disclaimer')->rows(2)->columnSpanFull(),
                        TextInput::make('fees.stage_image')->hidden(),
                        MediaPicker::forField('fees.stage_image', 'mba-masters-landing/fees')
                            ->label('Background plate image')
                            ->columnSpanFull(),
                        Repeater::make('fees.rows')
                            ->label('Fee rows')
                            ->schema([
                                TextInput::make('program')->label('Program')->required()->columnSpanFull(),
                                TextInput::make('duration')->label('Duration'),
                                TextInput::make('mode')->label('Study mode'),
                                TextInput::make('fee')->label('Fee range'),
                                TextInput::make('payment')->label('Payment option'),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['program'] ?? null)
                            ->addActionLabel('Add row')
                            ->columnSpanFull(),
                        TextInput::make('fees.cta_primary_label')->label('Primary CTA label'),
                        TextInput::make('fees.cta_primary_url')->label('Primary CTA URL'),
                        TextInput::make('fees.cta_secondary_label')->label('Secondary CTA label'),
                        TextInput::make('fees.cta_secondary_url')->label('Secondary CTA URL'),
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

        $existingMba = app(MbaMastersMbaSettings::class)->toArray();
        $existingMasters = app(MbaMastersMastersSettings::class)->toArray();

        $overview = $this->syncImageIfSelected($data['overview'] ?? [], 'plate_image');
        $overview['items'] = array_values($overview['items'] ?? []);

        $why = $data['why'] ?? [];
        $why['chapters'] = array_values($why['chapters'] ?? []);

        $journey = $data['journey'] ?? [];
        $journey['steps'] = array_values($journey['steps'] ?? []);

        $mba = $this->syncImageIfSelected($data['mba'] ?? [], 'stage_image');
        foreach ($mba['tabs'] ?? [] as $ti => &$tab) {
            $tab['universities'] = $this->hydrateRepeaterMediaFields($tab['universities'] ?? [], 'logo');
            $tab['universities'] = $this->hydrateRepeaterMediaFields($tab['universities'] ?? [], 'image');
            foreach ($tab['universities'] ?? [] as &$uni) {
                $uni = $this->syncImageIfSelected($uni, 'logo');
                $uni = $this->syncImageIfSelected($uni, 'image');
                $uni['programs'] = array_values($uni['programs'] ?? []);
            }
            unset($uni);
            $tab['universities'] = $this->preserveRepeaterImageFields(
                array_values($tab['universities'] ?? []),
                $existingMba['tabs'][$ti]['universities'] ?? [],
                'logo'
            );
            $tab['universities'] = $this->preserveRepeaterImageFields(
                $tab['universities'],
                $existingMba['tabs'][$ti]['universities'] ?? [],
                'image'
            );
        }
        unset($tab);
        $mba['tabs'] = array_values($mba['tabs'] ?? []);

        $masters = $this->syncImageIfSelected($data['masters'] ?? [], 'stage_image');
        $masters['universities'] = $this->hydrateRepeaterMediaFields($masters['universities'] ?? [], 'logo');
        $masters['universities'] = $this->hydrateRepeaterMediaFields($masters['universities'] ?? [], 'image');
        foreach ($masters['universities'] ?? [] as &$uni) {
            $uni = $this->syncImageIfSelected($uni, 'logo');
            $uni = $this->syncImageIfSelected($uni, 'image');
            $uni['programs'] = array_values($uni['programs'] ?? []);
        }
        unset($uni);
        $masters['universities'] = $this->preserveRepeaterImageFields(
            array_values($masters['universities'] ?? []),
            $existingMasters['universities'] ?? [],
            'logo'
        );
        $masters['universities'] = $this->preserveRepeaterImageFields(
            $masters['universities'],
            $existingMasters['universities'] ?? [],
            'image'
        );

        $fees = $this->syncImageIfSelected($data['fees'] ?? [], 'stage_image');
        $fees['rows'] = array_values($fees['rows'] ?? []);

        $ok = $this->saveSettingsGroup(MbaMastersOverviewSettings::class, $overview)
            && $this->saveSettingsGroup(MbaMastersWhySettings::class, $why)
            && $this->saveSettingsGroup(MbaMastersJourneySettings::class, $journey)
            && $this->saveSettingsGroup(MbaMastersMbaSettings::class, $mba)
            && $this->saveSettingsGroup(MbaMastersMastersSettings::class, $masters)
            && $this->saveSettingsGroup(MbaMastersFeesSettings::class, $fees);

        if ($ok) {
            $this->notifySaved('Programs');
        }
    }
}
