@php
    $statePath = $getStatePath();
    $state = $getState();
    $asset = filled($state) ? \App\Models\MediaAsset::query()->find($state) : null;
    $urlField = $getUrlField();
    $urlStatePath = null;

    if (filled($urlField)) {
        $urlStatePath = str_contains($statePath, '.')
            ? \Illuminate\Support\Str::beforeLast($statePath, '.').'.'.$urlField
            : $urlField;
    }

    $clearAction = "\$set('{$statePath}', null)";
    if ($urlStatePath) {
        $clearAction .= "; \$set('{$urlStatePath}', null)";
    }
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div
        wire:key="media-picker-{{ $statePath }}-{{ $state ?? 'empty' }}"
        x-data
        x-on:media-asset-selected.window="
            if ($event.detail.statePath === @js($statePath)) {
                $wire.set(@js($statePath), $event.detail.assetId);
                if ($event.detail.modalId) {
                    $dispatch('close-modal', { id: $event.detail.modalId });
                } else {
                    document.querySelector('.fi-modal-close-btn')?.click();
                }
            }
        "
        class="fi-fo-media-picker space-y-3"
    >
        @if ($asset)
            <div class="flex items-center gap-3 rounded-xl border border-gray-200 bg-white p-3 dark:border-white/10 dark:bg-white/5">
                <img
                    src="{{ $asset->url }}"
                    alt="{{ $asset->original_name ?: 'Selected media' }}"
                    class="h-16 w-16 rounded-lg object-cover ring-1 ring-gray-950/5 dark:ring-white/10"
                />
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-medium text-gray-950 dark:text-white">
                        {{ $asset->original_name ?: 'Asset #'.$asset->id }}
                    </p>
                    @if ($asset->folder)
                        <p class="truncate text-xs text-gray-500 dark:text-gray-400">
                            {{ $asset->folder }}
                        </p>
                    @endif
                </div>
                <x-filament::button
                    color="gray"
                    size="sm"
                    type="button"
                    wire:click="{{ $clearAction }}"
                >
                    Clear
                </x-filament::button>
            </div>
        @else
            <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 px-4 py-6 text-center dark:border-white/10 dark:bg-white/5">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    No image selected
                </p>
            </div>
        @endif

        <div class="flex flex-wrap gap-2">
            <x-filament::button
                color="gray"
                size="sm"
                type="button"
                icon="heroicon-m-photo"
                wire:click="mountFormComponentAction('{{ $statePath }}', 'chooseFromLibrary')"
            >
                Choose from Library
            </x-filament::button>

            <x-filament::button
                color="primary"
                size="sm"
                type="button"
                icon="heroicon-m-arrow-up-tray"
                wire:click="mountFormComponentAction('{{ $statePath }}', 'uploadNew')"
            >
                Upload New
            </x-filament::button>
        </div>
    </div>
</x-dynamic-component>