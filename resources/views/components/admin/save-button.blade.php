@props(['target' => 'save', 'label' => 'Save'])
<x-filament::button
    type="submit"
    color="primary"
    wire:target="{{ $target }}"
    wire:loading.attr="disabled"
    wire:loading.class="opacity-70 cursor-not-allowed"
>
    {{ $label }}
</x-filament::button>
