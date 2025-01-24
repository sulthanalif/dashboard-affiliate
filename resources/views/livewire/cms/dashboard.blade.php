<?php

use Mary\Traits\Toast;
use App\Models\Affiliate;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

new class extends Component {
    use Toast;

    public bool $myModal = false;
    public $id;
    public string $titleModal = '';
    public string $funcModal = '';

    //table
    public array $selected = [];
    public array $sortBy = ['column' => 'created_at', 'direction' => 'desc'];
    public int $perPage = 10;

    public int $affiliateAktif = 0;
    public int $affiliatePending = 0;
    public int $affiliateTotal = 0;
    public int $affiliateRejected = 0;

    public function mount(): void
    {
        $this->affiliateAktif = Affiliate::where('is_active', true)->count();
        $this->affiliatePending = Affiliate::where('is_active', false)->count();
        $this->affiliateTotal = Affiliate::count();
        $this->affiliateRejected = Affiliate::where('is_rejected', true)->count();

    }

    public function setModal($modal, $func, $id): void
    {
        // dd($modal, $func, $id);
        $this->myModal = true;
        $this->titleModal = $modal;
        $this->funcModal = $func;
        $this->id = $id;
    }

    public function approve(): void
    {
        $affiliate = Affiliate::find($this->id);
        if ($affiliate) {
            try {
                DB::beginTransaction();
                $affiliate->update(['is_active' => 1, 'is_rejected' => 0]);
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
                Log::log("error", $th->getMessage());
            }
            $this->success('Affiliate approved.', position: 'toast-bottom');
        }
        $this->reset('selected');
        $this->myModal = false;
    }

    public function reject(): void
    {
        try {
            DB::beginTransaction();
            Affiliate::find($this->id)->update(['is_active' => 0, 'is_rejected' => 1]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::log("error", $th->getMessage());
        }
        $this->success('Affiliate approved.', position: 'toast-bottom');
        $this->reset('selected');
        $this->myModal = false;
    }

    public function datas(): LengthAwarePaginator
    {
        return Affiliate::with('bank')
            ->where('is_active', false)
            ->where('is_rejected', false)
            ->latest()->paginate(10);
    }

    public function headers(): array
    {
        return [
            ['key' => 'username', 'label' => 'Username', 'class' => 'w-64'],
            ['key' => 'first_name', 'label' => 'Nama'],
            ['key' => 'email', 'label' => 'E-mail'],
            ['key' => 'phone', 'label' => 'No Telepon'],
            ['key' => 'address', 'label' => 'Alamat'],
            ['key' => 'bank.code', 'label' => 'Bank']
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
    <x-header title="Dashboard" separator progress-indicator>
        {{-- <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" />
        </x-slot:actions> --}}
    </x-header>

    <div class="py-4 rounded-b-xl grid md:grid-cols-4 gap-5">
        <x-stat title="Affiliate Aktif" value="{{$affiliateAktif}}" icon="o-check-circle" />
        <x-stat title="Affiliate Pending" value="{{$affiliatePending}}" icon="o-clock" />
        <x-stat title="Total Affiliate" value="{{$affiliateTotal}}" icon="o-users" />
        <x-stat title="Affiliate Rejected" value="{{$affiliateRejected}}" icon="o-x-circle" />
    </div>
    <x-card>
    <x-table
        :headers="$headers"
        :rows="$datas"
        :sort-by="$sortBy"
        per-page="perPage"
        :per-page-values="[5, 10, 50]"
        with-pagination
        >
            @scope('cell_username', $data)
                <x-badge value="{{ $data['username'] }}" class="badge-primary cursor-pointer" @click="$wire.show({{ $data['id'] }})" />
            @endscope
            @scope('cell_first_name', $data)
                {{ $data['first_name'] }} {{ $data['last_name'] }}
            @endscope
            @scope('actions', $data)
                <div class="flex gap-2">
                    @can('affiliate-reject')
                        <x-button tooltip="Reject" icon="o-x-mark" @click="$wire.setModal('menolak', 'reject', {{$data['id']}})" class="text-red-500 text-sm" />
                    @endcan
                    @can('affiliate-approve')
                        <x-button tooltip="Approve" icon="o-check" @click="$wire.setModal('menerima', 'approve', {{$data['id']}})" class="text-green-500 text-sm" />
                    @endcan
                </div>
            @endscope
            <x-slot:empty>
                <x-icon name="o-cube" label="Tidak Ada Data Terbaru." />
            </x-slot:empty>
        </x-table>
    </x-card>

    <!-- modal -->
    @include('livewire.cms.affiliates.alert')
</div>
