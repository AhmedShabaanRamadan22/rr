<?php

namespace Database\Seeders;

use App\Models\OrderSector;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderSectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 'order_id','sector_id','parent'
        OrderSector::create([//1 //!PARENT
            'order_id' => 1,//org = 1 service =1 اعاشة //الطهاة الخمس
            'sector_id'=> 1,
            // 'parent' => '1',
        ]);

        OrderSector::create([//2 //!PARENT
            'order_id' => 2,//org = 1,, service = 2 توريد //الطهاة الخمس
            'sector_id'=> 1,//org = 1
            // 'parent' => '0',
        ]);

        OrderSector::create([//3 //!PARENT
            'order_id' => 3,//org = 1,, service = 3 صيانة //الطهاة الخمس
            'sector_id'=> 3,//org = 1
            // 'parent' => '0',
        ]);

        OrderSector::create([//4 
            'order_id' => 6,//org = 1,, service = 3 صيانة //المطبخ العربي
            'sector_id'=> 3,//org = 1
            'parent_id' => 3,
        ]);

        OrderSector::create([//5 //!PARENT
            'order_id' => 7,//org = 1,, service = 1 اعاشة//مطبخ أذواق الحجاز
            'sector_id'=> 2,//org = 1
            // 'parent' => 1,
        ]);

        OrderSector::create([//6
            'order_id' => 8,//org = 1,, service = 2 توريد //مطبخ أذواق الحجاز
            'sector_id'=> 1,//org = 1
            'parent_id' => 2,
        ]);

        OrderSector::create([//7 //!PARENT
            'order_id' => 10,//org = 2,, service = 2 توريد //مطبخ أذواق الحجاز
            'sector_id'=> 4,//org = 2
            // 'parent' => '0',
        ]);

        OrderSector::create([//8
            'order_id' => 11,//org = 2,, service = 2 توريد //الطهاة الخمس
            'sector_id'=> 4,//org = 2
            'parent_id' => 7,
        ]);

        OrderSector::create([//9
            'order_id' => 4,//org = 1,, service = 1 اعاشة //المطبخ العربي
            'sector_id'=> 2,//org = 1
            'parent_id' => 5,
        ]);

        OrderSector::create([//10
            'order_id' => 12,//org = 1,, service = 1 اعاشة //المطبخ العربي
            'sector_id'=> 1,//org = 1
            'parent_id' => 2,
        ]);

        
    }
}
