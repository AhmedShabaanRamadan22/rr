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
        //local banks
        Bank::create(['name_ar' => 'البنك الأهلي السعودي', 'name_en' => 'Saudi National Bank']);
        Bank::create(['name_ar' => 'البنك العربي السعودي', 'name_en' => 'Arab National Bank']);
        Bank::create(['name_ar' => 'بنك الجزيرة', 'name_en' => 'Bank AlJazira']);
        Bank::create(['name_ar' => 'البنك السعودي الفرنسي', 'name_en' => 'Saudi Fransi Bank']);
        Bank::create(['name_ar' => 'مصرف الراجحي', 'name_en' => 'Al Rajhi Bank']);
        Bank::create(['name_ar' => 'بنك الرياض', 'name_en' => 'Riyadh Bank']);
        Bank::create(['name_ar' => 'بنك البلاد', 'name_en' => 'Bank Albilad']);
        Bank::create(['name_ar' => 'مصرف ساب', 'name_en' => 'SAAB Bank']);
        Bank::create(['name_ar' => 'بنك سامبا المالي', 'name_en' => 'SAMBA Financial Group']);
        Bank::create(['name_ar' => 'بنك الإنماء', 'name_en' => 'Alinma Bank']);
        Bank::create(['name_ar' => 'البنك السعودي للاستثمار', 'name_en' => 'Saudi Investment Bank']);
        Bank::create(['name_ar' => 'بنك الخليج الدولي', 'name_en' => 'Gulf International Bank']);
        //digital banks:
        Bank::create(['name_ar' => 'بنك إس تي سي', 'name_en' => 'STC bank']);
        Bank::create(['name_ar' => 'بنك فيجن', 'name_en' => 'Vision Bank']);
        Bank::create(['name_ar' => 'بنك دال ثلاثمائة وستون', 'name_en' => 'D360 Bank']);
        //foreign banks
        Bank::create(['name_ar' => 'بنك الامارات دبي الوطني', 'name_en' => 'Emirates NBD']);
        Bank::create(['name_ar' => 'بنك البحرين الوطني', 'name_en' => 'National Bank of Bahrain']);
        Bank::create(['name_ar' => 'بنك الكويت الوطني', 'name_en' => 'National Bank of Kuwait']);
        Bank::create(['name_ar' => 'بنك مسقط', 'name_en' => 'Muscat Bank']);
        Bank::create(['name_ar' => 'دويتشه بنك', 'name_en' => 'Deutsche Bank']);
        Bank::create(['name_ar' => 'بنك بي إن بي باريبا', 'name_en' => 'BNP Pariba']);
        Bank::create(['name_ar' => 'جي بي مورقان تشيز إن ايه', 'name_en' => 'J.P. Morgan Chase N.A']);
        Bank::create(['name_ar' => 'بنك باكستان الوطني', 'name_en' => 'National Bank Of Pakistan']);
        Bank::create(['name_ar' => 'بنك تي سي زراعات بانكاسي', 'name_en' => 'T.C.ZIRAAT BANKASI A.S']);
        Bank::create(['name_ar' => 'بنك الصين للصناعة والتجارة', 'name_en' => 'Industrial and Commercial Bank of China']);
        Bank::create(['name_ar' => 'بنك قطر الوطني', 'name_en' => 'Qatar National Bank']);
        Bank::create(['name_ar' => 'بنك إم يو إف جي المحدودة', 'name_en' => 'MUFG Bank, Ltd']);
        Bank::create(['name_ar' => 'بنك أبو ظبي الأول', 'name_en' => 'First Abu Dhabi Bank']);
        Bank::create(['name_ar' => 'بنك كريدت سويس', 'name_en' => 'Credit Suisse Bank']);
        Bank::create(['name_ar' => 'بنك ستاندرد تشارترد', 'name_en' => 'Standard Chartered Bank']);
        Bank::create(['name_ar' => 'المصرف الأهلي العراقي', 'name_en' => 'National Bank of Iraq']);
        // Bank::create(['name_ar' => '', 'name_en' => '']);
        

    }
}
