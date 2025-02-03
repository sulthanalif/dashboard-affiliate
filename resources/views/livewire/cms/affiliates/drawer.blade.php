<x-drawer wire:model="drawer" title="{{ $recordId == null ? 'Tambah' : 'Ubah' }} Affiliate" right separator with-close-button class="lg:w-1/3">
    <x-form wire:submit="save">
        <div>
            @if ($recordId == null)
            <x-input label="Nama Pengguna" type="text" wire:model="username" >
            <x-slot:append>
                <x-button label="Check" @click="$wire.checkUsername" class="btn-primary rounded-s-none" />
            </x-slot:append>
            </x-input>
            @else
            <x-input label="Nama Pengguna" type="text" wire:model="username" readonly />
            @endif
        </div>
        <div class="flex flex-no-wrap gap-4">
            <x-input label="Nama Depan" type="text" wire:model="first_name" />
            <x-input label="Nama Belakang" type="text" wire:model="last_name" />
        </div>
        <div>
            @if ($recordId == null)
            <x-input label="Email" type="email" wire:model="email" />
            @else
            <x-input label="Email" type="email" wire:model="email" readonly />
            @endif
        </div>
        <div>
            <x-textarea
            label="Alamat"
            wire:model="address"
            rows="2"/>
        </div>
        @if ($recordId == null)
        <div>
            <x-password label="Password" wire:model="password" right  />
        </div>
        <div>
            <x-password label="Konfirmasi Password" wire:model="password_confirmation" right  />
        </div>

        @else
        <div></div>
            <x-input label="Password" type="text" wire:model="password" />
        </div>
        @endif
        <div>
            <x-input label="Nomor Telepon" type="number" wire:model="phone" />
        </div>
        <div>
            <x-select
            label="Bank"
            :options="$banks"
            placeholder="Pilih Bank"
            placeholder-value="0"
            wire:model="bank_id" />
        </div>
        <div>
            <x-input label="Nomor Rekening" type="number" wire:model="account_number" />
        </div>
        <div>
            <x-input label="Nama Rekening" type="text" wire:model="account_name" />
        </div>
        <div>
            <x-select
                label="Status"
                :options="$selectAktif"
                wire:model="is_active" />
        </div>
        <x-slot:actions>
            <x-button label="Save" icon="o-check" class="btn-primary" type="submit" spinner="save" />
        </x-slot:actions>
    </x-form>
</x-drawer>
