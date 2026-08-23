<x-filament-panels::page>
    <style>[x-cloak]{display:none!important}</style>
    <div x-data="{ activeTab: 'content' }" class="space-y-6">
        <div class="flex flex-wrap gap-2 border-b border-gray-200 pb-3">
            <button type="button" @click="activeTab='content'"
                :class="activeTab==='content'?'bg-primary-600 text-white':'bg-white text-gray-600 hover:bg-gray-50'"
                class="rounded-lg px-4 py-2 text-sm font-medium">Page Content</button>
            <button type="button" @click="activeTab='universities'"
                :class="activeTab==='universities'?'bg-primary-600 text-white':'bg-white text-gray-600 hover:bg-gray-50'"
                class="rounded-lg px-4 py-2 text-sm font-medium">Partner Universities</button>
            <button type="button" @click="activeTab='gallery'"
                :class="activeTab==='gallery'?'bg-primary-600 text-white':'bg-white text-gray-600 hover:bg-gray-50'"
                class="rounded-lg px-4 py-2 text-sm font-medium">Partnership Gallery</button>
        </div>

        <div x-show="activeTab==='content'" x-cloak>
            <form wire:submit="save">
                {{ $this->form }}
                <div class="fi-section mt-6 p-4">
                    <x-admin.save-button label="Save Page Content" />
                </div>
            </form>
        </div>
        <div x-show="activeTab==='universities'" x-cloak>
            <livewire:gup-partner-university-table />
        </div>
        <div x-show="activeTab==='gallery'" x-cloak>
            <livewire:partnership-gallery-item-table />
        </div>
    </div>
</x-filament-panels::page>
