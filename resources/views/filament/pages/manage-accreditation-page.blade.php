<x-filament-panels::page>
    <style>[x-cloak]{display:none!important}</style>
    <div x-data="{ activeTab: 'cinematic' }" class="space-y-6">
        <div class="flex flex-wrap gap-2 border-b border-gray-200 pb-3">
            <button type="button" @click="activeTab='cinematic'"
                :class="activeTab==='cinematic'?'bg-primary-600 text-white':'bg-white text-gray-600 hover:bg-gray-50'"
                class="rounded-lg px-4 py-2 text-sm font-medium">Cinematic Section</button>
            <button type="button" @click="activeTab='awards'"
                :class="activeTab==='awards'?'bg-primary-600 text-white':'bg-white text-gray-600 hover:bg-gray-50'"
                class="rounded-lg px-4 py-2 text-sm font-medium">Awards &amp; Recognition</button>
        </div>

        <div x-show="activeTab==='cinematic'" x-cloak>
            <form wire:submit="save">
                {{ $this->form }}
                <div class="fi-section mt-6 p-4">
                    <x-admin.save-button label="Save Cinematic Section" />
                </div>
            </form>
        </div>
        <div x-show="activeTab==='awards'" x-cloak>
            <livewire:accreditation-award-table />
        </div>
    </div>
</x-filament-panels::page>
