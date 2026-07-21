<x-filament-panels::page>
    <form wire:submit="save">
        <div class="space-y-4">
            @foreach($faqs as $index => $faq)
                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">Question {{ $index + 1 }}</label>
                        <input type="text"
                               wire:model="faqs.{{ $index }}.question"
                               class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-sm px-3 py-2"
                               placeholder="Question..." />
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">Answer</label>
                        <textarea wire:model="faqs.{{ $index }}.answer"
                                  rows="3"
                                  class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-sm px-3 py-2"
                                  placeholder="Answer..."></textarea>
                    </div>
                    <button type="button"
                            wire:click="removeFaq({{ $index }})"
                            class="text-red-500 text-sm hover:underline">
                        Remove
                    </button>
                </div>
            @endforeach

            <button type="button"
                    wire:click="addFaq"
                    class="w-full p-3 border-2 border-dashed border-gray-300 rounded-lg text-gray-500 hover:border-primary-500 hover:text-primary-500 transition-colors">
                + Add FAQ
            </button>
        </div>
    </form>
</x-filament-panels::page>