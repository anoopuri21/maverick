<x-filament-panels::page>
    <style>[x-cloak]{display:none!important}</style>
    <div x-data="{ activeTab: 'settings' }" class="space-y-6">
        <div class="flex flex-wrap gap-2 border-b border-gray-200 pb-3">
            <button type="button"
                @click="activeTab = 'settings'"
                :class="activeTab === 'settings' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                class="rounded-lg px-4 py-2 text-sm font-medium">
                Section Copy
            </button>
            <button type="button"
                @click="activeTab = 'countries'"
                :class="activeTab === 'countries' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                class="rounded-lg px-4 py-2 text-sm font-medium">
                Countries
            </button>
        </div>

        <div x-show="activeTab === 'settings'" x-cloak>
            <form wire:submit="save">
                {{ $this->form }}
                <div class="fi-section mt-6 p-4">
                    <x-admin.save-button label="Save Section Copy" />
                </div>
            </form>
        </div>

        <div x-show="activeTab === 'countries'" x-cloak>
            <livewire:global-access-point-country-table />
        </div>
    </div>
</x-filament-panels::page>
