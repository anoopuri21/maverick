<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="fi-section mt-6 p-4">
            <x-admin.save-button label="Save FAQs" />
        </div>
    </form>
</x-filament-panels::page>
