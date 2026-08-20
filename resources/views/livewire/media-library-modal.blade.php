<div class="fi-media-library-modal space-y-4">
    <div class="flex gap-2 border-b border-gray-200 dark:border-white/10">
        <button
            type="button"
            wire:click="setTab('browse')"
            @class([
                'px-3 py-2 text-sm font-medium border-b-2 -mb-px transition',
                'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400' => $tab === 'browse',
                'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' => $tab !== 'browse',
            ])
        >
            Browse
        </button>
        <button
            type="button"
            wire:click="setTab('upload')"
            @class([
                'px-3 py-2 text-sm font-medium border-b-2 -mb-px transition',
                'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400' => $tab === 'upload',
                'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' => $tab !== 'upload',
            ])
        >
            Upload
        </button>
    </div>

    @if ($tab === 'browse')
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
            <div class="flex-1">
                <label class="fi-fo-field-wrp-label inline-flex mb-1">
                    <span class="text-sm font-medium text-gray-950 dark:text-white">Search</span>
                </label>
                <x-filament::input.wrapper>
                    <x-filament::input
                        type="search"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Name or public ID…"
                    />
                </x-filament::input.wrapper>
            </div>

            <div class="sm:w-56">
                <label class="fi-fo-field-wrp-label inline-flex mb-1">
                    <span class="text-sm font-medium text-gray-950 dark:text-white">Folder</span>
                </label>
                <x-filament::input.wrapper>
                    <x-filament::input.select
                        wire:model.live="folderFilter"
                        :disabled="$showAllFolders"
                    >
                        <option value="">All folders</option>
                        @foreach ($this->folderOptions as $value => $label)
                            <option value="{{ $value }}" {{ $folderFilter === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>

            <label class="inline-flex items-center gap-2 pb-2 text-sm text-gray-700 dark:text-gray-300">
                <input
                    type="checkbox"
                    wire:model.live="showAllFolders"
                    class="fi-checkbox-input rounded border-gray-300 text-primary-600 shadow-sm dark:border-white/10"
                />
                Show all folders
            </label>
        </div>

        <div wire:loading.flex wire:target="search,folderFilter,showAllFolders,gotoPage,previousPage,nextPage,setTab" class="hidden justify-center py-8">
            <x-filament::loading-indicator class="h-6 w-6" />
        </div>

        <div wire:loading.remove wire:target="search,folderFilter,showAllFolders,gotoPage,previousPage,nextPage">
            @if ($assets->isEmpty())
                <div class="rounded-xl border border-dashed border-gray-300 px-4 py-10 text-center text-sm text-gray-500 dark:border-white/10 dark:text-gray-400">
                    No media assets found for this environment.
                </div>
            @else
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4">
                    @foreach ($assets as $asset)
                        <button
                            type="button"
                            wire:click="selectAsset({{ $asset->id }})"
                            class="group overflow-hidden rounded-xl border border-gray-200 bg-white text-left transition hover:border-primary-500 hover:ring-2 hover:ring-primary-500/20 dark:border-white/10 dark:bg-white/5 dark:hover:border-primary-400"
                        >
                            <div class="aspect-square overflow-hidden bg-gray-100 dark:bg-gray-800">
                                <img
                                    src="{{ $asset->url }}"
                                    alt="{{ $asset->original_name ?: 'Media' }}"
                                    class="h-full w-full object-cover transition group-hover:scale-105"
                                    loading="lazy"
                                />
                            </div>
                            <div class="truncate px-2 py-1.5 text-xs text-gray-700 dark:text-gray-300">
                                {{ $asset->original_name ?: 'Asset #'.$asset->id }}
                            </div>
                        </button>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $assets->links() }}
                </div>
            @endif
        </div>
    @endif

    @if ($tab === 'upload')
        <div class="space-y-4">
            <div
                x-data="{ dragging: false }"
                x-on:dragover.prevent="dragging = true"
                x-on:dragleave.prevent="dragging = false"
                x-on:drop.prevent="dragging = false"
                class="rounded-xl border border-dashed border-gray-300 p-6 dark:border-white/10"
                :class="dragging ? 'border-primary-500 bg-primary-50 dark:bg-primary-400/10' : 'bg-gray-50 dark:bg-white/5'"
            >
                <div class="flex flex-col items-center gap-3 text-center">
                    <x-filament::icon
                        icon="heroicon-o-arrow-up-tray"
                        class="h-8 w-8 text-gray-400"
                    />
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        Drag & drop an image here, or browse
                    </p>
                    <input
                        type="file"
                        accept="image/*"
                        wire:model="upload"
                        class="block w-full max-w-sm text-sm text-gray-500 file:mr-3 file:rounded-lg file:border-0 file:bg-primary-600 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-primary-500"
                    />
                    @error('upload')
                        <p class="text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="upload" class="text-xs text-gray-500">
                        Preparing upload…
                    </div>
                </div>

                @if ($upload)
                    <div class="mt-4 flex flex-col items-center gap-2">
                        @if (method_exists($upload, 'isPreviewable') && $upload->isPreviewable())
                            <img
                                src="{{ $upload->temporaryUrl() }}"
                                alt="Preview"
                                class="max-h-40 rounded-lg object-contain"
                            />
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $upload->getClientOriginalName() }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex justify-end gap-2">
                <x-filament::button
                    color="primary"
                    type="button"
                    wire:click="saveUpload"
                    wire:loading.attr="disabled"
                    :disabled="! $upload || $uploading"
                >
                    <span wire:loading.remove wire:target="saveUpload">Upload & Select</span>
                    <span wire:loading wire:target="saveUpload">Uploading…</span>
                </x-filament::button>
            </div>

            @if ($folder)
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Uploads go to folder: <span class="font-medium">{{ $folder }}</span>
                </p>
            @endif
        </div>
    @endif
</div>