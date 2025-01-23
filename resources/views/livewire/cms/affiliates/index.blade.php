<?php

use App\ManageDatas;
use App\Models\Bank;
use Mary\Traits\Toast;
use App\Models\Affiliate;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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

    //select
    public Collection $banks;

    //varAffiliate
    public string $username = '';
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $phone = '';
    public string $address = '';
    public string $account_number = '';
    public string $account_name = '';
    public string $bank_id = '';
    public string $password = '';
    public string $password_confirmation = '';
    public array $varAffiliate = ['username', 'first_name', 'last_name', 'email', 'phone', 'address', 'account_number', 'account_name', 'bank_id', 'password', 'password_confirmation'];

    public function mount(): void
    {
        $this->banks = Bank::all();
    }

    public function checkUsername(): void
    {
        $existsUsername = Affiliate::where('username', $this->username)->exists();

        if ($existsUsername) {
            $this->warning('Username sudah digunakan.', position: 'toast-bottom');
        }

        $this->success('Username tersedia.', position: 'toast-bottom');
    }

    public function save(): void
    {
        $this->setModel(new Affiliate());

        $this->saveOrUpdate(
            validationRules: [
                'username' => ['required', 'string', 'max:255', 'unique:affiliates', function ($attribute, $value, $fail) {
                    if (preg_match('/\s/', $value)) {
                        $fail('The ' . $attribute . ' cannot contain spaces.');
                    }
                }],
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:affiliates'],
                'phone' => ['required', 'string', 'max:20'],
                'address' => ['required', 'string', 'max:500'],
                'account_number' => ['required', 'string', 'max:255'],
                'account_name' => ['required', 'string', 'max:255'],
                'bank_id' => ['required'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ],

            beforeSave: function ($affiliate, $component) {
                $affiliate->username = $component->username;
                $affiliate->first_name = $component->first_name;
                $affiliate->last_name = $component->last_name;
                $affiliate->email = $component->email;
                $affiliate->phone = $component->phone;
                $affiliate->address = $component->address;
                $affiliate->account_number = $component->account_number;
                $affiliate->account_name = $component->account_name;
                $affiliate->bank_id = $component->bank_id;
                $affiliate->password = Hash::make($component->password);
            },
        );

        $this->unsetModel();
        $this->reset($this->varAffiliate);
    }

    public function delete():void
    {
        $this->setModel(new Affiliate());

        foreach ($this->selected as $id) {
            $this->setRecordId($id);
            $this->deleteData();
        }
        $this->reset('selected');
        $this->unsetRecordId();
        $this->unsetModel();
        $this->myModal = false;
    }

    public function create(): void
    {
        $this->reset($this->varAffiliate, 'recordId');
        $this->drawer = true;
    }

    public function show($id): void
    {
        $affiliate = Affiliate::find($id);

        $this->recordId = $id;
        $this->username = $affiliate->username;
        $this->first_name = $affiliate->first_name;
        $this->last_name = $affiliate->last_name;
        $this->email = $affiliate->email;
        $this->phone = $affiliate->phone;
        $this->address = $affiliate->address;
        $this->account_number = $affiliate->account_number;
        $this->account_name = $affiliate->account_name;
        $this->bank_id = $affiliate->bank_id;
        $this->password = '';
        $this->password_confirmation = '';
        $this->drawer = true;
    }

    public function datas(): LengthAwarePaginator
    {
        return Affiliate::query()
            ->with('bank')
            ->where(function ($query) {
                $query->where('username', 'like', "%{$this->search}%")
                    ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhereHas('bank', function ($query) {
                        $query->where('name', 'like', "%{$this->search}%");
                    });
            })
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate($this->perPage);
    }

    public function headers(): array
    {
        return [
            ['key' => 'username', 'label' => 'Username', 'class' => 'w-64'],
            ['key' => 'first_name', 'label' => 'Nama'],
            ['key' => 'email', 'label' => 'E-mail'],
            ['key' => 'phone', 'label' => 'No Telepon'],
            ['key' => 'address', 'label' => 'Alamat'],
            ['key' => 'bank.name', 'label' => 'Bank']
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
    <x-header title="Data Affiliate" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            @can('affiliate-create')
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
            @scope('cell_username', $data)
                <x-badge value="{{ $data['username'] }}" class="badge-primary cursor-pointer" @click="$wire.show({{ $data['id'] }})" />
            @endscope
            @scope('cell_first_name', $data)
                {{ $data['first_name'] }} {{ $data['last_name'] }}
            @endscope
            <x-slot:empty>
                <x-icon name="o-cube" label="It is empty." />
            </x-slot:empty>
        </x-table>
        @can('affiliate-delete')
            @if ($this->selected)
                <div class="mt-2">
                    <x-button label="Hapus" icon="o-trash" @click="$wire.myModal = true" class="btn-ghost  text-red-500" />
                </div>
            @endif
        @endcan
    </x-card>

    <!-- DRAWER -->
     @include('livewire.cms.affiliates.drawer')

     <!-- MODAL -->
     @include('livewire.cms.affiliates.alertDelete')
</div>
