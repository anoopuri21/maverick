<?php

namespace App\Filament\Pages\MbaMastersLanding;

use App\Filament\Concerns\SavesSettingsGroups;
use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Pages\MbaMastersLanding\Concerns\ManagesMbaMastersChunk;
use App\Settings\MbaMastersCareerSettings;
use App\Settings\MbaMastersClassSettings;
use App\Settings\MbaMastersVideoTestimonialsSettings;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;

class ManageAudience extends Page implements HasForms
{
    use InteractsWithForms;
    use ManagesMbaMastersChunk;
    use SavesSettingsGroups;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Landing Pages';

    protected static ?string $navigationLabel = 'MBA Masters — Audience';

    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.mba-masters-landing.chunk';

    public function mount(): void
    {
        $class = app(MbaMastersClassSettings::class)->toArray();
        $class['metrics'] = array_values($class['metrics'] ?? []);
        $class['regions'] = array_values($class['regions'] ?? []);
        $class['industries'] = $this->hydrateRepeaterMediaFields(array_values($class['industries'] ?? []), 'image');

        $career = app(MbaMastersCareerSettings::class)->toArray();
        $career['stories'] = $this->hydrateRepeaterMediaFields(array_values($career['stories'] ?? []), 'portrait');

        $videoTestimonials = app(MbaMastersVideoTestimonialsSettings::class)->toArray();
        $videoTestimonials['videos'] = array_values($videoTestimonials['videos'] ?? []);

        $this->form->fill([
            'class' => $class,
            'career' => $career,
            'videoTestimonials' => $videoTestimonials,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->chunkHint('Edits class profile, career stories and video testimonials only.'),
                Section::make('Class profile')
                    ->schema([
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
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? $state['value'] ?? null)
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
                                MediaPicker::forField('image', 'mba-masters-landing/class/industries')->label('Industry image'),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->addActionLabel('Add industry')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsed()
                    ->collapsible(),
                Section::make('Career progression')
                    ->schema([
                        TextInput::make('career.label')->label('Section label'),
                        TextInput::make('career.heading')->label('Heading')->columnSpanFull(),
                        Textarea::make('career.intro')->label('Intro')->rows(2)->columnSpanFull(),
                        Repeater::make('career.stories')
                            ->label('Career direction cards')
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
                    ])
                    ->columns(2)
                    ->collapsed()
                    ->collapsible(),
                Section::make('Video testimonials')
                    ->description('4 YouTube videos that open in a popup — same pattern as the homepage.')
                    ->schema([
                        TextInput::make('videoTestimonials.label')->label('Section label'),
                        TextInput::make('videoTestimonials.heading')->label('Heading')->columnSpanFull(),
                        Textarea::make('videoTestimonials.intro')->label('Intro')->rows(2)->columnSpanFull(),
                        Repeater::make('videoTestimonials.videos')
                            ->label('Videos (4 recommended)')
                            ->schema([
                                TextInput::make('video_url')
                                    ->label('YouTube video URL')
                                    ->required()
                                    ->columnSpanFull()
                                    ->helperText('Paste the YouTube watch/share URL. The thumbnail is generated automatically unless you provide one.'),
                                TextInput::make('name')->label('Name'),
                                TextInput::make('role')->label('Role / designation'),
                                TextInput::make('category')->label('Badge / category')->placeholder('Student'),
                                TextInput::make('thumbnail')->label('Custom thumbnail URL (optional)')->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->addActionLabel('Add video')
                            ->columnSpanFull(),
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

        $existingClass = app(MbaMastersClassSettings::class)->toArray();
        $existingCareer = app(MbaMastersCareerSettings::class)->toArray();

        $class = $data['class'] ?? [];
        $class['metrics'] = array_values($class['metrics'] ?? []);
        $class['regions'] = array_values($class['regions'] ?? []);
        $class['industries'] = $this->hydrateRepeaterMediaFields($class['industries'] ?? [], 'image');
        foreach ($class['industries'] ?? [] as &$industry) {
            $industry = $this->syncImageIfSelected($industry, 'image');
        }
        unset($industry);
        $class['industries'] = $this->preserveRepeaterImageFields(
            array_values($class['industries'] ?? []),
            $existingClass['industries'] ?? [],
            'image'
        );

        $career = $data['career'] ?? [];
        $career['stories'] = $this->hydrateRepeaterMediaFields($career['stories'] ?? [], 'portrait');
        foreach ($career['stories'] ?? [] as &$story) {
            $story = $this->syncImageIfSelected($story, 'portrait');
        }
        unset($story);
        $career['stories'] = $this->preserveRepeaterImageFields(
            array_values($career['stories'] ?? []),
            $existingCareer['stories'] ?? [],
            'portrait'
        );

        $videoTestimonials = $data['videoTestimonials'] ?? [];
        $videoTestimonials['videos'] = array_values($videoTestimonials['videos'] ?? []);

        // Save each group independently so one failing group never silently
        // skips the others (each failure already raises its own notification).
        $ok = true;
        foreach ([
            [MbaMastersClassSettings::class, $class],
            [MbaMastersCareerSettings::class, $career],
            [MbaMastersVideoTestimonialsSettings::class, $videoTestimonials],
        ] as [$settingsClass, $payload]) {
            $ok = $this->saveSettingsGroup($settingsClass, $payload) && $ok;
        }

        if ($ok) {
            $this->notifySaved('Audience');
        }
    }
}
