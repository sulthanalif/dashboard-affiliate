<?php

use Mary\Traits\Toast;
use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};

new
#[Layout('...components.layouts.guest')]
#[Title('Register')]

class extends Component {
    use Toast;

}; ?>

<div>
    <div class="text-3xl font-bold text-black text-center mb-4">
        Selamat Datang
    </div>
    <div class="flex flex-no-wrap gap-4 justify-center">
        <div class="w-full lg:w-1/2 py-2">
            <div class="text-black text-center">
                <p>Selamat bergabung bersama kami, mohon menunggu konfirmasi dari kami.</p>
                <p>Selalu cek email anda.</p>
            </div>
        </div>
    </div>
</div>
