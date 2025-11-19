<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Order::create([//1
            'user_id' => 1,
            'organization_service_id' => 1, //org = 1,, service = 1 اعاشة
            'status_id' => Status::NEW_ORDER,
            'facility_id' => 1, //الطهاة الخمس
            // 'country_ids' => ['1','2'],
        ]);
        Order::create([//2
            'user_id' => 1,
            'organization_service_id' => 2, //org = 1,, service = 2 توريد
            'status_id' => Status::NEW_ORDER,
            'facility_id' => 1, //الطهاة الخمس
            'country_ids' => [4,3],
        ]);
        Order::create([//3
            'user_id' => 1,
            'organization_service_id' => 3, //org = 1,, service = 3 صيانة
            'status_id' => Status::NEW_ORDER,
            'facility_id' => 1, //الطهاة الخمس
            // 'country_ids' => ['4','6'],
        ]);
        Order::create([//4
            'user_id' => 2,
            'organization_service_id' => 1, //org = 1,, service = 1 اعاشة
            'status_id' => Status::NEW_ORDER,
            'facility_id' => 2, //المطبخ العربي
            // 'country_ids' => ['3','5','4'],
        ]);
        Order::create([//5
            'user_id' => 2,
            'organization_service_id' => 2, //org = 1,, service = 2 توريد
            'status_id' => Status::PROCESSING_ORDER,
            'facility_id' => 2, //المطبخ العربي
            // 'country_ids' => ['1','2'],
        ]);
        Order::create([//6
            'user_id' => 2,
            'organization_service_id' => 3, //org = 1,, service = 3 صيانة
            'status_id' => Status::CONFIRMED_ORDER,
            'facility_id' => 2, //المطبخ العربي
            'country_ids' => [1,7],
        ]);
        Order::create([//7
            'user_id' => 1,
            'organization_service_id' => 1, //org = 1,, service = 1 اعاشة
            'status_id' => Status::NEW_ORDER,
            'facility_id' => 3, //مطبخ أذواق الحجاز
            'country_ids' => [2,5],
        ]);
        Order::create([//8
            'user_id' => 1,
            'organization_service_id' => 2, //org = 1,, service = 2 توريد
            'status_id' => Status::NEW_ORDER,
            'facility_id' => 3, //مطبخ أذواق الحجاز
            'country_ids' => [6,3],
        ]);
        Order::create([//9
            'user_id' => 1,
            'organization_service_id' => 4, //org = 2,, service = 1 اعاشة
            'status_id' => Status::NEW_ORDER,
            'facility_id' => 3, //مطبخ أذواق الحجاز
            // 'country_ids' => ['8','9'],
        ]);
        Order::create([//10
            'user_id' => 1,
            'organization_service_id' => 5, //org = 2,, service = 2 توريد
            'status_id' => Status::NEW_ORDER,
            'facility_id' => 3, //مطبخ أذواق الحجاز
            'country_ids' => [10,11],
        ]);
        Order::create([//11
            'user_id' => 1,
            'organization_service_id' => 5, //org = 2,, service = 2 توريد
            'status_id' => Status::NEW_ORDER,
            'facility_id' => 1, //الطهاة الخمس
            'country_ids' => [13,12],
        ]);
        Order::create([//12
            'user_id' => 1,
            'organization_service_id' => 2, //org = 1,, service = 2 توريد
            'status_id' => Status::APPROVED_ORDER,
            'facility_id' => 2, //المطبخ العربي
            'country_ids' => [3,6],
        ]);

        // for ($i = 0; $i < 50; $i++) {
        //     Order::create([
        //         'user_id' => 2,
        //         'organization_service_id' => rand(1,3),
        //         'status_id' => Status::NEW_ORDER,
        //         'facility_id' => rand(1, 30), // Replace with actual facility IDs in your system
        //         'country_ids' => [rand(1, 7), rand(1, 7)], // Replace with actual country IDs in your system
        //     ]);
        // }
        // for ($i = 0; $i < 50; $i++) {
        //     Order::create([
        //         'user_id' => 2,
        //         'organization_service_id' => rand(4,6),
        //         'status_id' => Status::NEW_ORDER,
        //         'facility_id' => rand(1, 30), // Replace with actual facility IDs in your system
        //         'country_ids' => [rand(8, 13), rand(8, 13)], // Replace with actual country IDs in your system
        //     ]);
        // }
        
    }
}
