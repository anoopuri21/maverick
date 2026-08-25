<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\SavesSettingsGroups;
use App\Filament\Forms\Components\MediaPicker;
use App\Settings\MbaMastersAlumniSettings;
use App\Settings\MbaMastersLearningSettings;
use App\Settings\MbaMastersPartnersSettings;
use App\Settings\MbaMastersTestimonialsSettings;
use App\Settings\MbaMastersCompareSettings;
use App\Settings\MbaMastersFaqSettings;
use App\Settings\MbaMastersFinalSettings;
use App\Settings\MbaMastersCareerSettings;
use App\Settings\MbaMastersClassSettings;
use App\Settings\MbaMastersFeesSettings;
use App\Settings\MbaMastersHeroSettings;
use App\Settings\MbaMastersJourneySettings;
use App\Settings\MbaMastersMastersSettings;
use App\Settings\MbaMastersMbaSettings;
use App\Settings\MbaMastersOverviewSettings;
use App\Settings\MbaMastersSeoSettings;
use App\Settings\MbaMastersTrustSettings;
use App\Settings\MbaMastersWhySettings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Validation\ValidationException;
use Throwable;

class ManageMbaMastersLanding extends Page implements HasForms
{
    use SavesSettingsGroups;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Landing Pages';

    protected static ?string $navigationLabel = 'MBA & Master\'s Landing (legacy)';

    protected static ?int $navigationSort = 99;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.pages.manage-mba-masters-landing';

    public ?array $data = [];

    public function mount(): void
    {
        $trust = app(MbaMastersTrustSettings::class)->toArray();
        $trust['stats'] = array_values($trust['stats'] ?? []);

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
            foreach ($tab['universities'] as &$uni) {
                $uni['programs'] = array_values($uni['programs'] ?? []);
            }
            unset($uni);
        }
        unset($tab);

        $masters = app(MbaMastersMastersSettings::class)->toArray();
        $masters['universities'] = array_values($masters['universities'] ?? []);
        foreach ($masters['universities'] as &$uni) {
            $uni['programs'] = array_values($uni['programs'] ?? []);
        }
        unset($uni);

        $fees = app(MbaMastersFeesSettings::class)->toArray();
        $fees['rows'] = array_values($fees['rows'] ?? []);

        $class = app(MbaMastersClassSettings::class)->toArray();
        $class['metrics'] = array_values($class['metrics'] ?? []);
        $class['regions'] = array_values($class['regions'] ?? []);
        $class['industries'] = array_values($class['industries'] ?? []);

        $career = app(MbaMastersCareerSettings::class)->toArray();
        $career['stories'] = array_values($career['stories'] ?? []);

        $this->form->fill([
            'hero' => app(MbaMastersHeroSettings::class)->toArray(),
            'trust' => $trust,
            'overview' => $overview,
            'why' => $why,
            'journey' => $journey,
            'mba' => $mba,
            'masters' => $masters,
            'fees' => $fees,
            'class' => $class,
            'career' => $career,
            'alumni' => app(MbaMastersAlumniSettings::class)->toArray(),
            'learning' => (function () {
                $learning = app(MbaMastersLearningSettings::class)->toArray();
                $learning['points'] = array_values($learning['points'] ?? []);

                return $learning;
            })(),
            'partners' => app(MbaMastersPartnersSettings::class)->toArray(),
            'testimonials' => (function () {
                $testimonials = app(MbaMastersTestimonialsSettings::class)->toArray();
                $testimonials['items'] = array_values($testimonials['items'] ?? []);

                return $testimonials;
            })(),
            'compare' => (function () {
                $compare = app(MbaMastersCompareSettings::class)->toArray();
                $compare['rows'] = array_values($compare['rows'] ?? []);

                return $compare;
            })(),
            'faq' => (function () {
                $faq = app(MbaMastersFaqSettings::class)->toArray();
                $faq['items'] = array_values($faq['items'] ?? []);

                return $faq;
            })(),
            'final' => app(MbaMastersFinalSettings::class)->toArray(),
            'seo' => app(MbaMastersSeoSettings::class)->toArray(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('MbaMastersLanding')
                    ->tabs([
                        Tab::make('Hero')
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
                            ])->columns(2),
                        Tab::make('Trust stats')
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
                                    ->addActionLabel('Add stat')
                                    ->columnSpanFull(),
                            ]),
                        Tab::make('Program overview')
                            ->schema([
                                TextInput::make('overview.index')->label('Section index')->placeholder('03'),
                                TextInput::make('overview.label')->label('Section label'),
                                TextInput::make('overview.heading')->label('Heading')->columnSpanFull(),
                                Textarea::make('overview.intro')->label('Intro')->rows(2)->columnSpanFull(),
                                Repeater::make('overview.items')
                                    ->schema([
                                        TextInput::make('title')->label('Title')->required(),
                                        Textarea::make('text')->label('Text')->rows(2)->columnSpanFull(),
                                    ])
                                    ->defaultItems(0)
                                    ->reorderable()
                                    ->collapsible()
                                    ->addActionLabel('Add rail')
                                    ->columnSpanFull(),
                                TextInput::make('overview.cta_primary_label')->label('Primary CTA label'),
                                TextInput::make('overview.cta_primary_url')->label('Primary CTA URL'),
                                TextInput::make('overview.cta_secondary_label')->label('Secondary CTA label'),
                                TextInput::make('overview.cta_secondary_url')->label('Secondary CTA URL'),
                            ])->columns(2),
                        Tab::make('Why choose')
                            ->schema([
                                TextInput::make('why.index')->label('Section index')->placeholder('04'),
                                TextInput::make('why.label')->label('Section label'),
                                TextInput::make('why.heading')->label('Heading')->columnSpanFull(),
                                Textarea::make('why.intro')->label('Intro')->rows(2)->columnSpanFull(),
                                Repeater::make('why.chapters')
                                    ->schema([
                                        TextInput::make('title')->label('Title')->required(),
                                        Textarea::make('text')->label('Text')->rows(2)->columnSpanFull(),
                                        TextInput::make('anchor')->label('Optional link (#mlp-mba)')->placeholder('#mlp-enquire'),
                                    ])
                                    ->defaultItems(0)
                                    ->reorderable()
                                    ->collapsible()
                                    ->addActionLabel('Add chapter')
                                    ->columnSpanFull(),
                            ])->columns(2),
                        Tab::make('Admission journey')
                            ->schema([
                                TextInput::make('journey.index')->label('Section index')->placeholder('05'),
                                TextInput::make('journey.label')->label('Section label'),
                                TextInput::make('journey.heading')->label('Heading')->columnSpanFull(),
                                Textarea::make('journey.intro')->label('Intro')->rows(2)->columnSpanFull(),
                                Repeater::make('journey.steps')
                                    ->schema([
                                        TextInput::make('title')->label('Title')->required(),
                                        Textarea::make('text')->label('Text')->rows(2)->columnSpanFull(),
                                    ])
                                    ->defaultItems(0)
                                    ->reorderable()
                                    ->collapsible()
                                    ->addActionLabel('Add step')
                                    ->columnSpanFull(),
                                TextInput::make('journey.cta_label')->label('CTA label'),
                                TextInput::make('journey.cta_url')->label('CTA URL'),
                            ])->columns(2),
                        Tab::make('MBA specializations')
                            ->schema([
                                TextInput::make('mba.index')->label('Section index')->placeholder('06'),
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
                                                TextInput::make('logo')->hidden(),
                                                MediaPicker::forField('logo', 'mba-masters-landing/mba/logos')
                                                    ->label('Logo'),
                                                TextInput::make('image')->hidden(),
                                                MediaPicker::forField('image', 'mba-masters-landing/mba/campuses')
                                                    ->label('Campus / plate image'),
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
                            ])->columns(2),
                        Tab::make('Master\'s programs')
                            ->schema([
                                TextInput::make('masters.index')->label('Section index')->placeholder('07'),
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
                                        TextInput::make('logo')->hidden(),
                                        MediaPicker::forField('logo', 'mba-masters-landing/masters/logos')
                                            ->label('Logo'),
                                        TextInput::make('image')->hidden(),
                                        MediaPicker::forField('image', 'mba-masters-landing/masters/campuses')
                                            ->label('Campus / plate image'),
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
                            ])->columns(2),
                        Tab::make('Fees & payment')
                            ->schema([
                                TextInput::make('fees.index')->label('Section index')->placeholder('08'),
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
                            ])->columns(2),
                        Tab::make('Class profile')
                            ->schema([
                                TextInput::make('class.index')->label('Section index')->placeholder('09'),
                                TextInput::make('class.label')->label('Section label'),
                                TextInput::make('class.heading')->label('Heading')->columnSpanFull(),
                                Textarea::make('class.intro')->label('Intro')->rows(2)->columnSpanFull(),
                                Textarea::make('class.audience')->label('Audience line')->rows(2)->columnSpanFull(),
                                Repeater::make('class.metrics')
                                    ->label('Profile metrics')
                                    ->schema([
                                        TextInput::make('value')->label('Value')->required(),
                                        TextInput::make('label')->label('Label'),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(0)
                                    ->reorderable()
                                    ->addActionLabel('Add metric')
                                    ->columnSpanFull(),
                                Repeater::make('class.regions')
                                    ->label('Regions / countries')
                                    ->schema([
                                        TextInput::make('name')->label('Name')->required(),
                                        TextInput::make('note')->label('Note'),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(0)
                                    ->reorderable()
                                    ->addActionLabel('Add region')
                                    ->columnSpanFull(),
                                Repeater::make('class.industries')
                                    ->label('Industries')
                                    ->schema([
                                        TextInput::make('name')->label('Industry')->required(),
                                        TextInput::make('share')->label('Share %')->placeholder('22'),
                                        TextInput::make('image')->hidden(),
                                        MediaPicker::forField('image', 'mba-masters-landing/class/industries')
                                            ->label('Industry image'),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(0)
                                    ->reorderable()
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                                    ->addActionLabel('Add industry')
                                    ->columnSpanFull(),
                            ])->columns(2),
                        Tab::make('Career progression')
                            ->schema([
                                TextInput::make('career.index')->label('Section index')->placeholder('10'),
                                TextInput::make('career.label')->label('Section label'),
                                TextInput::make('career.heading')->label('Heading')->columnSpanFull(),
                                Textarea::make('career.intro')->label('Intro')->rows(2)->columnSpanFull(),
                                Repeater::make('career.stories')
                                    ->label('Career stories')
                                    ->schema([
                                        TextInput::make('name')->label('Name')->required(),
                                        TextInput::make('country')->label('Country'),
                                        TextInput::make('program')->label('Program')->columnSpanFull(),
                                        TextInput::make('previous_role')->label('Previous role')->columnSpanFull(),
                                        TextInput::make('current_role')->label('Current role')->columnSpanFull(),
                                        Textarea::make('quote')->label('Impact quote')->rows(2)->columnSpanFull(),
                                        TextInput::make('portrait')->hidden(),
                                        MediaPicker::forField('portrait', 'mba-masters-landing/career/portraits')
                                            ->label('Portrait')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(0)
                                    ->reorderable()
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                                    ->addActionLabel('Add story')
                                    ->columnSpanFull(),
                            ])->columns(2),
                        Tab::make('Alumni')
                            ->schema([
                                TextInput::make('alumni.index')->label('Section index')->placeholder('11'),
                                TextInput::make('alumni.label')->label('Section label'),
                                TextInput::make('alumni.heading')->label('Heading')->columnSpanFull(),
                                Textarea::make('alumni.intro')->label('Intro')->rows(2)->columnSpanFull(),
                                Textarea::make('alumni.trust_line')->label('Trust line')->rows(2)->columnSpanFull()
                                    ->helperText('Employer logos are managed under Partner Logos (type: alumni).'),
                            ])->columns(2),
                        Tab::make('Learning experience')
                            ->schema([
                                TextInput::make('learning.index')->label('Section index')->placeholder('12'),
                                TextInput::make('learning.label')->label('Section label'),
                                TextInput::make('learning.heading')->label('Heading')->columnSpanFull(),
                                Textarea::make('learning.intro')->label('Intro')->rows(2)->columnSpanFull(),
                                TextInput::make('learning.plate_image')->hidden(),
                                MediaPicker::forField('learning.plate_image', 'mba-masters-landing/learning')
                                    ->label('Diagonal media plate')
                                    ->columnSpanFull(),
                                TextInput::make('learning.plate_caption')->label('Plate caption')->columnSpanFull(),
                                Repeater::make('learning.points')
                                    ->label('Learning points')
                                    ->schema([
                                        TextInput::make('title')->label('Title')->required(),
                                        Textarea::make('text')->label('Text')->rows(2)->columnSpanFull(),
                                    ])
                                    ->columns(1)
                                    ->defaultItems(0)
                                    ->reorderable()
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                                    ->addActionLabel('Add point')
                                    ->columnSpanFull(),
                                TextInput::make('learning.cta_primary_label')->label('Primary CTA label'),
                                TextInput::make('learning.cta_primary_url')->label('Primary CTA URL'),
                                TextInput::make('learning.cta_secondary_label')->label('Secondary CTA label'),
                                TextInput::make('learning.cta_secondary_url')->label('Secondary CTA URL'),
                            ])->columns(2),
                        Tab::make('University partners')
                            ->schema([
                                TextInput::make('partners.index')->label('Section index')->placeholder('13'),
                                TextInput::make('partners.label')->label('Section label'),
                                TextInput::make('partners.heading')->label('Heading')->columnSpanFull(),
                                Textarea::make('partners.intro')->label('Intro')->rows(2)->columnSpanFull(),
                                Textarea::make('partners.trust_line')->label('Trust line')->rows(2)->columnSpanFull()
                                    ->helperText('Logos come from University Partners (active records with logos). No duplicate Partner Logo resource.'),
                            ])->columns(2),
                        Tab::make('Testimonials')
                            ->schema([
                                TextInput::make('testimonials.index')->label('Section index')->placeholder('14'),
                                TextInput::make('testimonials.label')->label('Section label'),
                                TextInput::make('testimonials.heading')->label('Heading')->columnSpanFull(),
                                Textarea::make('testimonials.intro')->label('Intro')->rows(2)->columnSpanFull()
                                    ->helperText('Active Our Story testimonials are preferred when present; items below are placeholders until permission.'),
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
                            ])->columns(2),
                        Tab::make('Comparison')
                            ->schema([
                                TextInput::make('compare.index')->label('Section index')->placeholder('15'),
                                TextInput::make('compare.label')->label('Section label'),
                                TextInput::make('compare.heading')->label('Heading')->columnSpanFull(),
                                Textarea::make('compare.intro')->label('Intro')->rows(2)->columnSpanFull(),
                                TextInput::make('compare.col_online')->label('Online column label'),
                                TextInput::make('compare.col_traditional')->label('Traditional column label'),
                                Repeater::make('compare.rows')
                                    ->label('Comparison rows')
                                    ->schema([
                                        TextInput::make('criterion')->label('Criterion')->required(),
                                        Textarea::make('online')->label('Online')->rows(2),
                                        Textarea::make('traditional')->label('Traditional')->rows(2),
                                    ])
                                    ->columns(3)
                                    ->defaultItems(0)
                                    ->reorderable()
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['criterion'] ?? null)
                                    ->addActionLabel('Add row')
                                    ->columnSpanFull(),
                                TextInput::make('compare.cta_label')->label('CTA label'),
                                TextInput::make('compare.cta_url')->label('CTA URL'),
                            ])->columns(2),
                        Tab::make('FAQ')
                            ->schema([
                                TextInput::make('faq.index')->label('Section index')->placeholder('16'),
                                TextInput::make('faq.label')->label('Section label'),
                                TextInput::make('faq.heading')->label('Heading')->columnSpanFull(),
                                Repeater::make('faq.items')
                                    ->label('FAQ items')
                                    ->schema([
                                        TextInput::make('question')->label('Question')->required()->columnSpanFull(),
                                        Textarea::make('answer')->label('Answer')->rows(3)->columnSpanFull(),
                                    ])
                                    ->columns(1)
                                    ->defaultItems(0)
                                    ->reorderable()
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['question'] ?? null)
                                    ->addActionLabel('Add question')
                                    ->columnSpanFull(),
                            ])->columns(2),
                        Tab::make('Final CTA')
                            ->schema([
                                TextInput::make('final.index')->label('Section index')->placeholder('17'),
                                TextInput::make('final.label')->label('Section label'),
                                TextInput::make('final.heading')->label('Heading')->columnSpanFull(),
                                Textarea::make('final.intro')->label('Intro')->rows(2)->columnSpanFull(),
                                TextInput::make('final.plate_image')->hidden(),
                                MediaPicker::forField('final.plate_image', 'mba-masters-landing/final')
                                    ->label('Full-bleed background plate')
                                    ->columnSpanFull(),
                                TextInput::make('final.cta_primary_label')->label('Primary CTA label'),
                                TextInput::make('final.cta_primary_url')->label('Primary CTA URL'),
                                TextInput::make('final.cta_secondary_label')->label('Secondary CTA label'),
                                TextInput::make('final.cta_secondary_url')->label('Secondary CTA URL'),
                                Toggle::make('final.show_form')
                                    ->label('Show mini enquiry form')
                                    ->helperText('Posts to the same enquire route as the hero form.'),
                                TextInput::make('final.form_title')->label('Form title')->columnSpanFull(),
                            ])->columns(2),
                        Tab::make('SEO')
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
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            report($e);
            Notification::make()->title('Could not save')->danger()->send();

            return;
        }

        $hero = $this->syncImageIfSelected($data['hero'] ?? [], 'background_image');
        $seo = $this->syncImageIfSelected($data['seo'] ?? [], 'og_image_url');

        $mba = $this->syncImageIfSelected($data['mba'] ?? [], 'stage_image');
        foreach ($mba['tabs'] ?? [] as &$tab) {
            foreach ($tab['universities'] ?? [] as &$uni) {
                $uni = $this->syncImageIfSelected($uni, 'logo');
                $uni = $this->syncImageIfSelected($uni, 'image');
                $uni['programs'] = array_values($uni['programs'] ?? []);
            }
            unset($uni);
            $tab['universities'] = array_values($tab['universities'] ?? []);
        }
        unset($tab);
        $mba['tabs'] = array_values($mba['tabs'] ?? []);

        $masters = $this->syncImageIfSelected($data['masters'] ?? [], 'stage_image');
        foreach ($masters['universities'] ?? [] as &$uni) {
            $uni = $this->syncImageIfSelected($uni, 'logo');
            $uni = $this->syncImageIfSelected($uni, 'image');
            $uni['programs'] = array_values($uni['programs'] ?? []);
        }
        unset($uni);
        $masters['universities'] = array_values($masters['universities'] ?? []);

        $fees = $this->syncImageIfSelected($data['fees'] ?? [], 'stage_image');
        $fees['rows'] = array_values($fees['rows'] ?? []);

        $class = $data['class'] ?? [];
        $class['metrics'] = array_values($class['metrics'] ?? []);
        $class['regions'] = array_values($class['regions'] ?? []);
        foreach ($class['industries'] ?? [] as &$industry) {
            $industry = $this->syncImageIfSelected($industry, 'image');
        }
        unset($industry);
        $class['industries'] = array_values($class['industries'] ?? []);

        $career = $data['career'] ?? [];
        foreach ($career['stories'] ?? [] as &$story) {
            $story = $this->syncImageIfSelected($story, 'portrait');
        }
        unset($story);
        $career['stories'] = array_values($career['stories'] ?? []);

        $learning = $this->syncImageIfSelected($data['learning'] ?? [], 'plate_image');
        $learning['points'] = array_values($learning['points'] ?? []);

        $testimonials = $data['testimonials'] ?? [];
        foreach ($testimonials['items'] ?? [] as &$item) {
            $item = $this->syncImageIfSelected($item, 'photo');
        }
        unset($item);
        $testimonials['items'] = array_values($testimonials['items'] ?? []);

        $compare = $data['compare'] ?? [];
        $compare['rows'] = array_values($compare['rows'] ?? []);

        $faq = $data['faq'] ?? [];
        $faq['items'] = array_values($faq['items'] ?? []);

        $final = $this->syncImageIfSelected($data['final'] ?? [], 'plate_image');
        $final['show_form'] = (bool) ($final['show_form'] ?? true);

        $ok = $this->saveSettingsGroup(MbaMastersHeroSettings::class, $hero)
            && $this->saveSettingsGroup(MbaMastersTrustSettings::class, $data['trust'] ?? [])
            && $this->saveSettingsGroup(MbaMastersOverviewSettings::class, $data['overview'] ?? [])
            && $this->saveSettingsGroup(MbaMastersWhySettings::class, $data['why'] ?? [])
            && $this->saveSettingsGroup(MbaMastersJourneySettings::class, $data['journey'] ?? [])
            && $this->saveSettingsGroup(MbaMastersMbaSettings::class, $mba)
            && $this->saveSettingsGroup(MbaMastersMastersSettings::class, $masters)
            && $this->saveSettingsGroup(MbaMastersFeesSettings::class, $fees)
            && $this->saveSettingsGroup(MbaMastersClassSettings::class, $class)
            && $this->saveSettingsGroup(MbaMastersCareerSettings::class, $career)
            && $this->saveSettingsGroup(MbaMastersAlumniSettings::class, $data['alumni'] ?? [])
            && $this->saveSettingsGroup(MbaMastersLearningSettings::class, $learning)
            && $this->saveSettingsGroup(MbaMastersPartnersSettings::class, $data['partners'] ?? [])
            && $this->saveSettingsGroup(MbaMastersTestimonialsSettings::class, $testimonials)
            && $this->saveSettingsGroup(MbaMastersCompareSettings::class, $compare)
            && $this->saveSettingsGroup(MbaMastersFaqSettings::class, $faq)
            && $this->saveSettingsGroup(MbaMastersFinalSettings::class, $final)
            && $this->saveSettingsGroup(MbaMastersSeoSettings::class, $seo);

        if ($ok) {
            Notification::make()->title('MBA & Master\'s landing saved')->success()->send();
        }
    }

    protected function syncImageIfSelected(array $payload, string $field): array
    {
        if (! empty($payload["{$field}_asset_id"])) {
            return MediaPicker::syncFieldFromAsset($payload, $field);
        }

        return $payload;
    }
}
