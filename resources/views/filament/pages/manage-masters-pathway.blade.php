<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="fi-section mt-6 p-4">
            <x-filament::button type="submit" color="primary">
                Save Master's Pathway Page
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
