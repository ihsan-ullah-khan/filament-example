<x-filament-panels::page>
    <x-filament-panels::form wire:submit="updateProfile">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getFormActions()"
        />

        <!-- Profile Update Button -->
        <div class="flex items-center gap-4 mt-4">
            <x-filament-panels::form.actions
                :actions="$this->getFormActions()"
            />
        </div>
    </x-filament-panels::form>

</x-filament-panels::page>
