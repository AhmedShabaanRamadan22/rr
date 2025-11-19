<?php

namespace Database\Seeders;

use App\Models\Dictionary;
use Illuminate\Database\Seeder;

class DictionarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //? ------------------------------------------------------
        //? --------------------OrderSector-----------------------
        //? ------------------------------------------------------

        Dictionary::create([
            'value' => 'sector##classification##organization##name',
            'key_ar' => 'اسم الطرف الاول',
            'key_en' => '',
            'type' => 'order_sectors'
        ]);
        Dictionary::create([
            'value' => 'sector##classification##organization##registration_number',
            'key_ar' => 'السجل التجاري لطرف الاول',
            'key_en' => '',
            'type' => 'order_sectors'
        ]);
        Dictionary::create([
            'value' => 'sector##classification##organization##release_date_hj',
            'key_ar' => 'تاريخ السجل التجاري',
            'key_en' => '',
            'type' => 'order_sectors'
        ]);
        Dictionary::create([
            'value' => 'sector##classification##organization##city##name',
            'key_ar' => 'مكان اصدار السجل',
            'key_en' => '',
            'type' => 'order_sectors'
        ]);
        Dictionary::create([
            'value' => 'sector##classification##organization##national_address',
            'key_ar' => 'العنوان الوطني للطرف الاول ',
            'key_en' => '',
            'type' => 'order_sectors'
        ]);
        Dictionary::create([
            'value' => 'sector##classification##organization##email',
            'key_ar' => 'البريد الالكتروني للطرف الاول',
            'key_en' => '',
            'type' => 'order_sectors'
        ]);
        Dictionary::create([
            'value' => 'sector##classification##organization##phone',
            'key_ar' => 'رقم هاتف الطرف الاول',
            'key_en' => '',
            'type' => 'order_sectors'
        ]);
        Dictionary::create([
            'value' => 'organization##chairman##name',
            'key_ar' => 'اسم رئيس الطرف الاول',
            'key_en' => '',
            'type' => 'order_sectors'
        ]);
        Dictionary::create([
            'value' => 'organization##chairman##national_id',
            'key_ar' => 'رقم الهوية لرئيس الطرف الاول',
            'key_en' => '',
            'type' => 'order_sectors'
        ]);
        Dictionary::create([
            'value' => 'order##facility##name',
            'key_ar' => 'اسم الطرف الثاني',
            'key_en' => '',
            'type' => 'order_sectors'
        ]);
        Dictionary::create([
            'value' => 'order##facility##registration_number',
            'key_ar' => 'السجل التجاري للطرف الثاني',
            'key_en' => '',
            'type' => 'order_sectors'
        ]);
        Dictionary::create([
            'value' => 'order##facility##registration_source_name',
            'key_ar' => 'مكان اصدار السجل التجاري للطرف الثاني',
            'key_en' => '',
            'type' => 'order_sectors'
        ]);
        Dictionary::create([
            'value' => 'order##facility##version_date_hj',
            'key_ar' => 'تاريخ اصدار السجل التجاري للطرف الثاني بالهجري',
            'key_en' => '',
            'type' => 'order_sectors'
        ]);
        Dictionary::create([
            'value' => 'order##facility##national_address',
            'key_ar' => 'العنوان الوطني للطرف الثاني',
            'key_en' => '',
            'type' => 'order_sectors'
        ]);
        Dictionary::create([
            'value' => 'order##user##email',
            'key_ar' => 'البريد الالكتروني لللطرف الثاني',
            'key_en' => '',
            'type' => 'order_sectors'
        ]);
        Dictionary::create([
            'value' => 'order##user##phone',
            'key_ar' => 'رقم هاتف للطرف الثاني',
            'key_en' => '',
            'type' => 'order_sectors'
        ]);
        Dictionary::create([
            'value' => 'order##user##name',
            'key_ar' => 'اسم صاحب الطرف الثاني',
            'key_en' => '',
            'type' => 'order_sectors'
        ]);
        Dictionary::create([
            'value' => 'order##user##national_id',
            'key_ar' => 'رقم هوية صاحب الطرف الثاني',
            'key_en' => '',
            'type' => 'order_sectors'
        ]);
        // // Dictionary::create([
        // //     'value'=>'sector##contract##end_at',
        // //     'key_ar'=>'',
        // //     'key_en'=>''
        // // ]);
        Dictionary::create([
            'value' => 'sector##guest_quantity',
            'key_ar' => 'عدد الضيوف',
            'key_en' => '',
            'type' => 'order_sectors'
        ]);
        Dictionary::create([
            'value' => 'sector##classification##guest_value',
            'key_ar' => 'قيمة الحاج',
            'key_en' => '',
            'type' => 'order_sectors'
        ]);
        Dictionary::create([
            'value' => 'sector##cost_all',
            'key_ar' => 'التكلفة الإجمالية',
            'key_en' => '',
            'type' => 'order_sectors'
        ]);
        // Dictionary::create([
        //     'value'=>'sector##contract##sign_date',
        //     'key_ar'=>'',
        //     'key_en'=>''
        // ]);

        //? ------------------------------------------------------
        //? ---------------------Employess------------------------
        //? ------------------------------------------------------

        Dictionary::create([
            'value' => 'organization##name',
            'key_ar' => 'اسم الطرف الاول',
            'key_en' => '',
            'type' => 'users'
        ]);
        Dictionary::create([
            'value' => 'organization##registration_number',
            'key_ar' => 'السجل التجاري لطرف الاول',
            'key_en' => '',
            'type' => 'users'
        ]);
        Dictionary::create([
            'value' => 'organization##release_date_hj',
            'key_ar' => 'تاريخ السجل التجاري',
            'key_en' => '',
            'type' => 'users'
        ]);
        Dictionary::create([
            'value' => 'organization##city##name',
            'key_ar' => 'مكان اصدار السجل',
            'key_en' => '',
            'type' => 'users'
        ]);
        Dictionary::create([
            'value' => 'organization##national_address',
            'key_ar' => 'العنوان الوطني للطرف الاول ',
            'key_en' => '',
            'type' => 'users'
        ]);
        Dictionary::create([
            'value' => 'organization##email',
            'key_ar' => 'البريد الالكتروني للطرف الاول',
            'key_en' => '',
            'type' => 'users'
        ]);
        Dictionary::create([
            'value' => 'organization##phone',
            'key_ar' => 'رقم هاتف الطرف الاول',
            'key_en' => '',
            'type' => 'users'
        ]);
        Dictionary::create([
            'value' => 'organization##chairman##name',
            'key_ar' => 'اسم رئيس الطرف الاول',
            'key_en' => '',
            'type' => 'users'
        ]);
        Dictionary::create([
            'value' => 'organization##chairman##national_id',
            'key_ar' => 'رقم الهوية لرئيس الطرف الاول',
            'key_en' => '',
            'type' => 'users'
        ]);
        Dictionary::create([
            'value' => 'user##name',
            'key_ar' => 'اسم الطرف الثاني',
            'key_en' => '',
            'type' => 'users'
        ]);
        Dictionary::create([
            'value' => 'user##national_id',
            'key_ar' => 'رقم هوية الطرف الثاني',
            'key_en' => '',
            'type' => 'users'
        ]);
        Dictionary::create([
            'value' => 'user##phone',
            'key_ar' => 'رقم جوال الطرف الثاني',
            'key_en' => '',
            'type' => 'users'
        ]);
        Dictionary::create([
            'value' => 'user##address',
            'key_ar' => 'العنوان الوطني للطرف الثاني',
            'key_en' => '',
            'type' => 'users'
        ]);
        Dictionary::create([
            'value' => 'user##birthday',
            'key_ar' => 'تاريخ ميلاد الطرف الثاني',
            'key_en' => '',
            'type' => 'users'
        ]);
        Dictionary::create([
            'value' => 'user##nationality',
            'key_ar' => 'جنسية الطرف الثاني',
            'key_en' => '',
            'type' => 'users'
        ]);
        Dictionary::create([
            'value' => 'user##email',
            'key_ar' => 'البريد الالكتروني للطرف الثاني',
            'key_en' => '',
            'type' => 'users'
        ]);
        Dictionary::create([
            'value' => 'user##iban##account_name',
            'key_ar' => 'اسم الحساب البنكي للطرف الثاني',
            'key_en' => '',
            'type' => 'users'
        ]);
        Dictionary::create([
            'value' => 'user##iban##iban',
            'key_ar' => 'آيبان الطرف الثاني',
            'key_en' => '',
            'type' => 'users'
        ]);
        Dictionary::create([
            'value' => 'user##iban##bank_name',
            'key_ar' => 'اسم بنك الطرف الثاني',
            'key_en' => '',
            'type' => 'users'
        ]);
        Dictionary::create([
            'value' => 'user##scrub_size',
            'key_ar' => 'مقاس سكرب الطرف الثاني',
            'key_en' => '',
            'type' => 'users'
        ]);
        Dictionary::create([
            'value' => 'user##salary',
            'key_ar' => 'راتب الطرف الثاني',
            'key_en' => '',
            'type' => 'users'
        ]);
        Dictionary::create([
            'value' => 'user##monitor_position',
            'key_ar' => 'وظيفة الطرف الثاني',
            'key_en' => '',
            'type' => 'users'
        ]);
    }
}