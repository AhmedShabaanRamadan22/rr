<?php

namespace Database\Seeders;

use App\Models\Iban;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IbanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Iban::create([
            'account_name' => 'Reem Abdu Alotmi',
            'iban' => 'GB52ABCD12345678901234',
            'bank_id' => '2',
            'ibanable_id' => '1',
            'ibanable_type' => 'App\Models\Facility'
        ]);

        Iban::create([
            'account_name' => 'Jawad Mohammed Alghuraibi',
            'iban' => 'GB52ABCD99945678901234',
            'bank_id' => '3',
            'ibanable_id' => '2',
            'ibanable_type' => 'App\Models\Facility'
        ]);

        Iban::create([
            'account_name' => 'Lama Kamel Bugis',
            'iban' => 'GB52ABCD99945676661234',
            'bank_id' => '6',
            'ibanable_id' => '3',
            'ibanable_type' => 'App\Models\Facility'
        ]);

        Iban::create([
            'account_name' => 'Omar Ahmed Khan',
            'iban' => 'GB52ABCD99945445561234',
            'bank_id' => '2',
            'ibanable_id' => '4',
            'ibanable_type' => 'App\Models\Facility'
        ]);

        // Iban::create([
        //     'account_name' => 'Reem Abdu Alotmi',
        //     'iban' => 'GB52ABCD12345678901234',
        //     'bank_id' => '2',
        //     'ibanable_id' => '1',
        //     'ibanable_type' => 'App\Models\User'
        // ]);
    }
}
