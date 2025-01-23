<?php

use App\ManageDatas;
use App\Models\Bank;
use Mary\Traits\Toast;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;

new class extends Component {
    use Toast, ManageDatas, WithPagination;

    public string $search = '';

    public bool $drawer = false;
    public bool $myModal = false;

    //table
    public array $selected = [];
    public array $sortBy = ['column' => 'created_at', 'direction' => 'desc'];
    public int $perPage = 10;

    //varBank
    public string $code = '';
    public string $name = '';
    public array $varBank = ['code', 'name'];

    public function create(): void
    {
        $this->reset($this->varBank, 'recordId');
        $this->drawer = true;
    }

    public function show($id): void
    {
        $bank = Bank::find($id);

        $this->recordId = $id;
        $this->code = $bank->code;
        $this->name = $bank->name;
        $this->drawer = true;
    }

    public function save(): void
    {
        $this->setModel(new Bank());

        $this->saveOrUpdate(
            validationRules: [
                'code' => ['required', 'string', 'max:255', 'unique:banks', function ($attribute, $value, $fail) {
                    if (preg_match('/\s/', $value)) {
                        $fail('The ' . $attribute . ' cannot contain spaces.');
                    }
                }],
                'name' => ['required', 'string', 'max:255'],
            ],

            beforeSave: function ($bank, $component) {
                $bank->code = $component->code;
                $bank->name = $component->name;
            },
        );

        $this->reset($this->varBank);
        $this->unsetModel();
        $this->drawer = false;
    }

    public function delete():void
    {
        $this->setModel(new Bank());

        foreach ($this->selected as $id) {
            $this->setRecordId($id);
            $this->deleteData();
        }
        $this->reset('selected');
        $this->unsetRecordId();
        $this->unsetModel();
        $this->myModal = false;
    }

    public function datas(): LengthAwarePaginator
    {
        return Bank::query()
            ->where(function ($query) {
                $query->where('code', 'like', "%{$this->search}%")
                    ->where('name', 'like', "%{$this->search}%");
            })
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate($this->perPage);
    }

    public function headers(): array
    {
        return [
            ['key' => 'code', 'label' => 'Kode'],
            ['key' => 'name', 'label' => 'Nama'],
        ];
    }

    public function with(): array
    {
        return [
            'datas' => $this->datas(),
            'headers' => $this->headers(),
        ];
    }
}; ?>

<div>
    <!-- HEADER -->
    <x-header title="Data Bank" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            @can('bank-create')
                <x-button label="Tambah" @click="$wire.create" responsive icon="o-plus" />
            @endcan
        </x-slot:actions>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table
        :headers="$headers"
        :rows="$datas"
        :sort-by="$sortBy"
        per-page="perPage"
        :per-page-values="[5, 10, 50]"
        wire:model.live="selected"
        selectable
        with-pagination
        >
            @scope('cell_code', $data)
                <x-badge value="{{ $data['code'] }}" class="badge-primary cursor-pointer" @click="$wire.show({{ $data['id'] }})" />
            @endscope
            <x-slot:empty>
                <x-icon name="o-cube" label="It is empty." />
            </x-slot:empty>
        </x-table>
        @can('bank-delete')
            @if ($this->selected)
                <div class="mt-2">
                    <x-button label="Hapus" icon="o-trash" @click="$wire.myModal = true" spinner class="btn-ghost  text-red-500" wire:loading.attr="disabled" />
                </div>
            @endif
        @endcan
    </x-card>

    <!-- DRAWER -->
     @include('livewire.cms.banks.drawer')

     <!-- MODAL -->
     @include('livewire.cms.banks.alertDelete')
</div>
