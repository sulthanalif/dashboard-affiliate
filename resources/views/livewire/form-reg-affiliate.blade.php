<?php

use App\ManageDatas;
use App\Models\Bank;
use Mary\Traits\Toast;
use App\Models\Affiliate;
use Livewire\Volt\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\notificationRegAffiliate;
use Livewire\Attributes\{Layout, Title};

new
#[Layout('...components.layouts.guest')]
#[Title('Register')]

class extends Component {
    use Toast, ManageDatas;

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
    public bool $tnc = false;
    public array $varAffiliate = ['username', 'first_name', 'last_name', 'email', 'phone', 'address', 'account_number', 'account_name', 'bank_id', 'password', 'password_confirmation', 'tnc'];

    public function mount(): void
    {
        $this->banks = Bank::all();
    }

    public function register(): void
    {
        if (!$this->tnc) {
            $this->error('You must accept the terms and conditions.');
        }

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

          toast: false,

        );

        activity('affiliate')->log('Register Affiliate');

        $this->reset($this->varAffiliate);
        $this->success('Sukses daftar affiliate.', position: 'toast-bottom', redirectTo: route('after-reg'));
    }
}; ?>

<div>
    <div class="text-3xl font-bold text-black text-center mb-4">
        Register Affiliate
    </div>
    <div class="flex flex-no-wrap gap-4 justify-center">
    <form class="w-full lg:w-1/2 py-2" wire:submit="register">
        <div class="mb-4">
            <label for="username" class="text-black">Username*</label>
            <input type="text" required class="block w-full px-4 py-2 text-black bg-white border border-black rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" wire:model="username" >
            @error('username')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="first_name" class="text-black">Nama Depan*</label>
                <input type="text" required class="block w-full px-4 py-2 text-black bg-white border border-black rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" wire:model="first_name" >
                @error('first_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label for="last_name" class="text-black">Nama Belakang*</label>
                <input type="text" required class="block w-full px-4 py-2 text-black bg-white border border-black rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" wire:model="last_name" >
                @error('last_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="email" class="text-black">Email*</label>
                <input type="email" required class="block w-full px-4 py-2 text-black bg-white border border-black rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" wire:model="email" >
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label for="phone" class="text-black">Nomor Telepon*</label>
                <input type="number" required class="block w-full px-4 py-2 text-black bg-white border border-black rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" wire:model="phone" >
                @error('phone')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="mb-4">
            <label for="address" class="text-black">Alamat*</label>
            <textarea required rows="2" class="block w-full px-4 py-2 text-black bg-white border border-black rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" wire:model="address" ></textarea>
            @error('address')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label for="bank_id" class="text-black">Nama Bank*</label>
                <select required class="block w-full px-4 py-2 text-black bg-white border border-black rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" wire:model="bank_id" >
                    <option value="">-- Pilih Bank --</option>
                    @foreach ($banks as $bank)
                        <option value="{{ $bank->id }}">{{ $bank->code }} ({{ $bank->name }})</option>
                    @endforeach
                </select>
                @error('bank_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label for="account_number" class="text-black">Nomor Rekening*</label>
                <input type="text" required class="block w-full px-4 py-2 text-black bg-white border border-black rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" wire:model="account_number" >
                @error('account_number')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label for="account_name" class="text-black">Nama Pemilik Rekening*</label>
                <input type="text" required class="block w-full px-4 py-2 text-black bg-white border border-black rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" wire:model="account_name" >
                @error('account_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="mb-4">
            <label for="password" class="text-black">Password*</label>
            <input type="password" required class="block w-full px-4 py-2 text-black bg-white border border-black rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" wire:model="password" >
            @error('password')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label for="password_confirmation" class="text-black">Ulangi Password*</label>
            <input type="password" required class="block w-full px-4 py-2 text-black bg-white border border-black rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" wire:model="password_confirmation" >
            @error('password_confirmation')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
        <x-checkbox wire:model="tnc" class="checkbox-success">
            <x-slot:label>
                <a class="text-black hover:underline" href="https://bervin.co.id/terms-and-conditions/">Agree to Our Terms and Conditions</a>
            </x-slot:label>
        </x-checkbox>
        </div>
        <div class="flex justify-start gap-2">
            <button type="submit" class="inline-flex justify-center px-4 py-2 text-base font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500" wire:loading.attr="disabled" wire:target="save">
                Register
            </button>
            <a href="https://bervin.co.id/" class="inline-flex justify-center px-4 py-2 text-base font-medium text-white bg-gray-600 border border-transparent rounded-md shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">Kembali</a>
        </div>
    </form>
    </div>

    <!-- <button wire:click="testMail" class="inline-flex justify-center px-4 py-2 text-base font-medium text-white bg-gray-600 border border-transparent rounded-md shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">test mail</button> -->
</div>
