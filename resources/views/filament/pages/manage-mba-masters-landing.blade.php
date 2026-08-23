<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="fi-form-actions mt-6">
            <x-filament::button type="submit">
                Save changes
            </x-filament::button>
            <x-filament::button tag="a" color="gray" href="{{ url('/online-mba-masters-uae') }}" target="_blank">
                Preview page
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
