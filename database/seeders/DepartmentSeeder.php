<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Department::create( [
            'name_en' => 'IT Etqan - Information Technology',
            'name_ar' => 'إتقان الرقمية - تقنية المعلومات',
            'slug'    => 'ETQ',
             'head_id' => '1',
        ] );

        Department::create( [
            'name_en' => 'Quality Control - Food health and safety',
            'name_ar' => 'جودة التشغيل - صحة وسلامة الغذاء',
            'slug'    => 'QFSCO',
            'head_id' => '1',
        ] );

        Department::create( [
            'name_en' => 'Marketing',
            'name_ar' => 'تسويق',
            'slug'    => 'MRK',
             'head_id' => '1',
        ] );

        Department::create( [
            'name_en' => 'Public Relations',
            'name_ar' => 'علاقات عامة',
            'slug'    => 'PLR',
             'head_id' => '1',
        ] );

        Department::create( [
            'name_en' => 'Human Resources',
            'name_ar' => 'موارد بشرية',
            'slug'    => 'HR',
             'head_id' => '1',
        ] );

        Department::create( [
            'name_en' => 'Planning and Development',
            'name_ar' => 'تخطيط وتطوير',
            'slug'    => 'PD',
             'head_id' => '1',
        ] );

        Department::create( [
            'name_en' => 'Finance',
            'name_ar' => 'مالية',
            'slug'    => 'FIN',
             'head_id' => '1',
        ] );

        Department::create( [
            'name_en' => 'Administrative assistance',
            'name_ar' => 'مساند اداري',
            'slug'    => 'ADA',
             'head_id' => '1',
        ] );

        Department::create( [
            'name_en' => 'Legal',
            'name_ar' => 'قانوني',
            'slug'    => 'LGL',
             'head_id' => '1',
        ] );

        Department::create( [
            'name_en' => 'Photographer',
            'name_ar' => 'مصور',
            'slug'    => 'PHG',
             'head_id' => '1',
        ] );

        Department::create( [
            'name_en' => 'Purchases',
            'name_ar' => 'مشتريات',
            'slug'    => 'PUC',
             'head_id' => '1',
        ] );


    }
}
