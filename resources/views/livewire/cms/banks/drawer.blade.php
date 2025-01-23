<x-drawer wire:model="drawer" title="{{ $recordId == null ? 'Tambah' : 'Ubah' }} Bank" right separator with-close-button class="lg:w-1/3">
    <x-form wire:submit="save">
        <div>
            <x-input label="Kode Bank" type="text" wire:model="code" />
        </div>
        <div>
            <x-input label="Nama Bank" type="text" wire:model="name" />
        </div>
        <x-slot:actions>
            <x-button label="Save" icon="o-check" class="btn-primary" type="submit" spinner="save" />
        </x-slot:actions>
    </x-form>
</x-drawer>
