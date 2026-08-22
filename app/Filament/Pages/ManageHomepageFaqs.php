<?php

namespace App\Filament\Pages;

use App\Models\Faq;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageHomepageFaqs extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'Homepage';

    protected static ?string $navigationLabel = 'Homepage FAQs';

    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.pages.manage-homepage-faqs';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'faqs' => Faq::where('faqable_type', 'homepage')
                ->where('faqable_id', 1)
                ->orderBy('sort_order')
                ->get()
                ->map(fn ($faq) => [
                    'question' => $faq->question,
                    'answer' => $faq->answer,
                    'is_active' => $faq->is_active,
                ])
                ->toArray(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Repeater::make('faqs')
                    ->schema([
                        TextInput::make('question')->columnSpanFull(),
                        RichEditor::make('answer')->columnSpanFull(),
                        Toggle::make('is_active')->default(true),
                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['question'] ?? 'FAQ')
                    ->defaultItems(0),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        try {
            $faqs = data_get($this->form->getState(), 'faqs', []);

            \Illuminate\Support\Facades\DB::transaction(function () use ($faqs) {
                Faq::where('faqable_type', 'homepage')
                    ->where('faqable_id', 1)
                    ->delete();

                foreach ($faqs as $index => $faq) {
                    if (! is_array($faq)) {
                        continue;
                    }

                    Faq::create([
                        'faqable_type' => 'homepage',
                        'faqable_id' => 1,
                        'question' => data_get($faq, 'question', ''),
                        'answer' => data_get($faq, 'answer', ''),
                        'sort_order' => $index,
                        'is_active' => data_get($faq, 'is_active', true),
                    ]);
                }
            });

            Notification::make()
                ->title('FAQs saved successfully!')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            report($e);

            Notification::make()
                ->title('Could not save FAQs')
                ->danger()
                ->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save FAQs')
                ->action('save')
                ->color('primary'),
        ];
    }
}
