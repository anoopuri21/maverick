<x-filament-panels::page>
    <style>[x-cloak]{display:none!important}</style>
    <div x-data="{ activeTab: 'settings' }" class="space-y-6">
        {{-- Tab bar --}}
        <div class="flex flex-wrap gap-2 border-b border-gray-200 pb-3">
            <button type="button"
                @click="activeTab = 'settings'"
                :class="activeTab === 'settings' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                class="rounded-lg px-4 py-2 text-sm font-medium">
                Settings
            </button>
            <button type="button"
                @click="activeTab = 'timeline'"
                :class="activeTab === 'timeline' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                class="rounded-lg px-4 py-2 text-sm font-medium">
                Timeline
            </button>
            <button type="button"
                @click="activeTab = 'gallery'"
                :class="activeTab === 'gallery' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                class="rounded-lg px-4 py-2 text-sm font-medium">
                Gallery Images
            </button>
            <button type="button"
                @click="activeTab = 'testimonials'"
                :class="activeTab === 'testimonials' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                class="rounded-lg px-4 py-2 text-sm font-medium">
                Testimonials
            </button>
        </div>

        {{-- Settings tab (form) --}}
        <div x-show="activeTab === 'settings'" x-cloak>
            <form wire:submit="save">
                {{ $this->form }}
                <div class="fi-section mt-6 p-4">
                    <x-admin.save-button label="Save Our Story" />
                </div>
            </form>
        </div>

        {{-- Embedded CRUD tables --}}
        <div x-show="activeTab === 'timeline'" x-cloak>
            <livewire:our-story-timeline-table />
        </div>
        <div x-show="activeTab === 'gallery'" x-cloak>
            <livewire:our-story-gallery-image-table />
        </div>
        <div x-show="activeTab === 'testimonials'" x-cloak>
            <livewire:our-story-testimonial-table />
        </div>
    </div>
</x-filament-panels::page>
