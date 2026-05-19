<x-filament-panels::page>
    <div class="max-w-2xl">
        {{ $this->form }}

        <div class="pt-6">
            <x-filament::button type="submit" color="success" wire:click="save">
                {{ __('Save Changes') }}
            </x-filament::button>
        </div>
    </div>
</x-filament-panels::page>
