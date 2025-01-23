<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            [
                'code' => 'BNI',
                'name' => 'Bank Negara Indonesia'
            ],
            [
                'code' => 'BCA',
                'name' => 'Bank Central Asia'
            ],
            [
                'code' => 'BRI',
                'name' => 'Bank Rakyat Indonesia'
            ],
            [
                'code' => 'Mandiri',
                'name' => 'Bank Mandiri'
            ],
            [
                'code' => 'BNI',
                'name' => 'Bank Negara Indonesia'
            ],
        ];

        foreach ($banks as $bank) {
            Bank::create($bank);
        }
    }
}
