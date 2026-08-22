<?php

namespace App\Livewire;

use App\Models\MediaAsset;
use App\Services\MediaLibraryService;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Throwable;

class MediaLibraryModal extends Component
{
    use WithFileUploads;
    use WithPagination;

    public string $statePath;

    public ?string $folder = null;

    public string $initialTab = 'browse';

    public string $tab = 'browse';

    public string $search = '';

    public ?string $folderFilter = null;

    public bool $showAllFolders = false;

    public $upload = null;

    public bool $uploading = false;

    public function mount(string $statePath, ?string $folder = null, string $initialTab = 'browse'): void
    {
        $this->statePath = $statePath;
        $this->folder = $folder;
        $this->initialTab = in_array($initialTab, ['browse', 'upload'], true) ? $initialTab : 'browse';
        $this->tab = $this->initialTab;
        $this->folderFilter = $folder;
        $this->showAllFolders = blank($folder);
    }

    public function updatingSearch(): void
    {
        $this->resetPage(pageName: 'mediaPage');
    }

    public function updatingFolderFilter(): void
    {
        $this->resetPage(pageName: 'mediaPage');
    }

    public function updatingShowAllFolders(bool $value): void
    {
        $this->resetPage(pageName: 'mediaPage');

        if ($value) {
            $this->folderFilter = null;
        } elseif (filled($this->folder)) {
            $this->folderFilter = $this->folder;
        }
    }

    public function setTab(string $tab): void
    {
        if (! in_array($tab, ['browse', 'upload'], true)) {
            return;
        }

        $this->tab = $tab;
    }

    public function selectAsset(int $assetId): void
    {
        $asset = app(MediaLibraryService::class)
            ->scopeLibrary(MediaAsset::query())
            ->find($assetId);

        if (! $asset) {
            Notification::make()
                ->title('Asset not found')
                ->danger()
                ->send();

            return;
        }

        $this->emitSelection($asset->id);
    }

    public function saveUpload(): void
    {
        $this->validate([
            'upload' => ['required', 'image', 'max:5120'],
        ]);

        $this->uploading = true;

        try {
            $folder = $this->folder ?: 'general';
            $asset = app(MediaLibraryService::class)->store(
                $this->upload,
                $folder,
                $this->upload->getClientOriginalName(),
            );

            $reused = ! $asset->wasRecentlyCreated;

            Notification::make()
                ->title($reused
                    ? 'Image already exists in library — reusing existing asset'
                    : 'Uploaded successfully')
                ->success()
                ->send();

            $this->reset('upload');
            $this->emitSelection($asset->id);
        } catch (Throwable $e) {
            Notification::make()
                ->title('Upload failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        } finally {
            $this->uploading = false;
        }
    }

    protected function emitSelection(int $assetId): void
    {
        $this->dispatch(
            'media-asset-selected',
            assetId: $assetId,
            statePath: $this->statePath,
            modalId: null,
        );

        // Close the Filament form-component action modal hosting this component.
        $this->js('setTimeout(() => document.querySelector(".fi-modal-close-btn")?.click(), 50)');
    }

    #[Computed]
    public function folderOptions(): Collection
    {
        return app(MediaLibraryService::class)
            ->scopeLibrary(MediaAsset::query())
            ->whereNotNull('folder')
            ->where('folder', '!=', '')
            ->distinct()
            ->orderBy('folder')
            ->pluck('folder', 'folder');
    }

    public function render(): View
    {
        $query = app(MediaLibraryService::class)
            ->scopeLibrary(MediaAsset::query())
            ->orderByDesc('id');

        if (filled($this->search)) {
            $term = '%'.$this->search.'%';
            $query->where(function ($q) use ($term) {
                $q->where('original_name', 'like', $term)
                    ->orWhere('cloudinary_public_id', 'like', $term);
            });
        }

        if (! $this->showAllFolders && filled($this->folderFilter)) {
            $query->where('folder', $this->folderFilter);
        }

        return view('livewire.media-library-modal', [
            'assets' => $query->paginate(24, pageName: 'mediaPage'),
        ]);
    }
}
