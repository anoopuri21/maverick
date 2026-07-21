<?php

namespace App\Filament\Pages;

use App\Models\Faq;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ManageHomepageFaqs extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationGroup = 'Homepage';
    protected static ?string $navigationLabel = 'Homepage FAQs';
    protected static ?int $navigationSort = 10;
    protected static string $view = 'filament.pages.manage-homepage-faqs';

    public array $faqs = [];
    public function addFaq(): void
    {
        $this->faqs[] = [
            'question' => '',
            'answer' => '',
            'sort_order' => count($this->faqs),
            'is_active' => true,
        ];
    }

    public function removeFaq(int $index): void
    {
        array_splice($this->faqs, $index, 1);
    }
    
    public function mount(): void
    {
        $this->faqs = Faq::where('faqable_type', 'homepage')
            ->where('faqable_id', 1)
            ->orderBy('sort_order')
            ->get()
            ->map(fn($faq) => [
                'id' => $faq->id,
                'question' => $faq->question,
                'answer' => $faq->answer,
                'sort_order' => $faq->sort_order,
                'is_active' => $faq->is_active,
            ])
            ->toArray();
    }

    public function save(): void
    {
        // Delete existing homepage FAQs
        Faq::where('faqable_type', 'homepage')
            ->where('faqable_id', 1)
            ->delete();

        // Re-create from form data
        foreach ($this->faqs as $index => $faq) {
            Faq::create([
                'faqable_type' => 'homepage',
                'faqable_id' => 1,
                'question' => $faq['question'],
                'answer' => $faq['answer'],
                'sort_order' => $index,
                'is_active' => $faq['is_active'] ?? true,
            ]);
        }

        Notification::make()
            ->title('FAQs saved successfully!')
            ->success()
            ->send();
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