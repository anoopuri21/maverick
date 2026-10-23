<x-filament-panels::page>
    <div x-data="{ tab: 'settings' }" class="space-y-6">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex flex-wrap gap-6" aria-label="Our Story Page sections">
                <button type="button" @click="tab = 'settings'"
                    :class="tab === 'settings' ? 'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400'"
                    class="whitespace-nowrap border-b-2 px-1 pb-3 text-sm font-medium transition">
                    Page Copy &amp; Settings
                </button>
                <button type="button" @click="tab = 'timeline'"
                    :class="tab === 'timeline' ? 'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400'"
                    class="whitespace-nowrap border-b-2 px-1 pb-3 text-sm font-medium transition">
                    Timeline Milestones
                </button>
                <button type="button" @click="tab = 'gallery'"
                    :class="tab === 'gallery' ? 'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400'"
                    class="whitespace-nowrap border-b-2 px-1 pb-3 text-sm font-medium transition">
                    Gallery Images
                </button>
            </nav>
        </div>

        <div x-show="tab === 'settings'" x-cloak>
            <x-filament-panels::form wire:submit="save">
                {{ $this->form }}

                <div class="flex justify-end pt-4">
                    <x-filament::button type="submit" icon="heroicon-o-check">
                        Save Our Story Settings
                    </x-filament::button>
                </div>
            </x-filament-panels::form>
        </div>

        <div x-show="tab === 'timeline'" x-cloak>
            <livewire:our-story-timeline-table />
        </div>

        <div x-show="tab === 'gallery'" x-cloak>
            <livewire:our-story-gallery-table />
        </div>
    </div>
</x-filament-panels::page>
