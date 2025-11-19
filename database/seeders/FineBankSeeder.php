<?php

namespace Database\Seeders;

use App\Models\FineBank;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FineBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FineBank::create([
            'name' => 'تأخر في تسليم الوجبات',
            'price' => '599.99',
            'code' => 'DM-01',
        ]);
        FineBank::create([
            'name' => 'رشوة مراقب',
            'price' => '2000',
            'code' => 'BM-01',
        ]);
        FineBank::create([
            'name' => 'اختلاف في نوع الوجبة المطلوب',
            'price' => '390.50',
            'code' => 'DTM-01',
        ]);
    }
}
