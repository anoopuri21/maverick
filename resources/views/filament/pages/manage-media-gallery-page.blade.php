<x-filament-panels::page>
    <style>[x-cloak]{display:none!important}</style>
    <div x-data="{ activeTab: 'photos' }" class="space-y-6">
        <div class="flex flex-wrap gap-2 border-b border-gray-200 pb-3">
            <button type="button" @click="activeTab='photos'"
                :class="activeTab==='photos'?'bg-primary-600 text-white':'bg-white text-gray-600 hover:bg-gray-50'"
                class="rounded-lg px-4 py-2 text-sm font-medium">Gallery Photos</button>
            <button type="button" @click="activeTab='videos'"
                :class="activeTab==='videos'?'bg-primary-600 text-white':'bg-white text-gray-600 hover:bg-gray-50'"
                class="rounded-lg px-4 py-2 text-sm font-medium">Featured Videos</button>
        </div>

        <div x-show="activeTab==='photos'" x-cloak>
            <livewire:media-gallery-photo-table />
        </div>
        <div x-show="activeTab==='videos'" x-cloak>
            <livewire:media-gallery-video-table />
        </div>
    </div>
</x-filament-panels::page>
