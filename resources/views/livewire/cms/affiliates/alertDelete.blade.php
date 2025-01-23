{{--  modal --}}
<x-modal title="Hapus Data!" wire:model="myModal" class="backdrop-blur">
    <x-form wire:submit="delete" class="relative" no-separator>
        <div class="flex justify-center items-center">
            <div class="mb-5 rounded-lg p-6 w-full">
                <p>Apakah anda yakin ingin menghapus data ini?</p>
            </div>
        </div>
        <x-slot:actions>
            <x-button label="Tidak" @click="$wire.myModal = false" />
            <x-button label="Ya" class="btn-primary" type="submit" spinner="save" />
        </x-slot:actions>
    </x-form>
</x-modal>
