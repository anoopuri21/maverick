<?php

namespace App\Filament\Pages\MbaMastersLanding;

use App\Filament\Concerns\SavesSettingsGroups;
use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Pages\MbaMastersLanding\Concerns\ManagesMbaMastersChunk;
use App\Settings\MbaMastersFaqSettings;
use App\Settings\MbaMastersFinalSettings;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;

class ManageFaqClose extends Page implements HasForms
{
    use InteractsWithForms;
    use ManagesMbaMastersChunk;
    use SavesSettingsGroups;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Landing Pages';

    protected static ?string $navigationLabel = 'MBA Masters — FAQ & Close';

    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.pages.mba-masters-landing.chunk';

    public function mount(): void
    {
        $faq = app(MbaMastersFaqSettings::class)->toArray();
        $faq['items'] = array_values($faq['items'] ?? []);

        $this->form->fill([
            'faq' => $faq,
            'final' => app(MbaMastersFinalSettings::class)->toArray(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->chunkHint('Edits FAQ accordion and final CTA / mini form only.'),
                Section::make('FAQ')
                    ->schema([
                        TextInput::make('faq.label')->label('Section label'),
                        TextInput::make('faq.heading')->label('Heading')->columnSpanFull(),
                        Repeater::make('faq.items')
                            ->label('FAQ items')
                            ->schema([
                                TextInput::make('question')->label('Question')->required()->columnSpanFull(),
                                $this->richEditor('answer', 'Answer'),
                            ])
                            ->columns(1)
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['question'] ?? null)
                            ->addActionLabel('Add question')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsed()
                    ->collapsible(),
                Section::make('Final CTA')
                    ->schema([
                        TextInput::make('final.label')->label('Section label'),
                        TextInput::make('final.heading')->label('Heading')->columnSpanFull(),
                        $this->richEditor('final.intro', 'Intro'),
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
                            ->helperText('Posts to the same enquire route as the hero form (Zoho Mail).'),
                        TextInput::make('final.form_title')->label('Form title')->columnSpanFull(),
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

        $faq = $data['faq'] ?? [];
        $faq['items'] = array_values($faq['items'] ?? []);

        $final = $this->syncImageIfSelected($data['final'] ?? [], 'plate_image');
        $final['show_form'] = (bool) ($final['show_form'] ?? true);

        $ok = $this->saveSettingsGroup(MbaMastersFaqSettings::class, $faq)
            && $this->saveSettingsGroup(MbaMastersFinalSettings::class, $final);

        if ($ok) {
            $this->notifySaved('FAQ & Close');
        }
    }
}
