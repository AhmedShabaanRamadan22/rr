<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MonitorOrderSector;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MonitorOrderSectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //include only NULL PARENT order_sector_ids and of different services
        MonitorOrderSector::create([
            'monitor_id'=>1,
            'order_sector_id'=>1, //sector = 1  //اعاشة
        ]);
        MonitorOrderSector::create([
            'monitor_id'=>1,
            'order_sector_id'=>2, //sector = 1 //توريد
        ]);
        MonitorOrderSector::create([
            'monitor_id'=>1,
            'order_sector_id'=>3, //sector = 3 //صيانة
        ]);
        MonitorOrderSector::create([
            'monitor_id'=>1,
            'order_sector_id'=>5, //sector = 2 //اعاشة
        ]);
        MonitorOrderSector::create([
            'monitor_id'=>1,
            'order_sector_id'=>7, //sector = 4 //توريد
        ]);

        MonitorOrderSector::create([
            'monitor_id'=>2,
            'order_sector_id'=>1, //sector = 1  //اعاشة
        ]);
        MonitorOrderSector::create([
            'monitor_id'=>2,
            'order_sector_id'=>2, //sector = 1 //توريد
        ]);
        MonitorOrderSector::create([
            'monitor_id'=>2,
            'order_sector_id'=>3, //sector = 3 //صيانة
        ]);
        MonitorOrderSector::create([
            'monitor_id'=>2,
            'order_sector_id'=>5, //sector = 2 //اعاشة
        ]);
        MonitorOrderSector::create([
            'monitor_id'=>2,
            'order_sector_id'=>7, //sector = 4 //توريد
        ]);
    
        MonitorOrderSector::create([
            'monitor_id'=>3,
            'order_sector_id'=>1, //sector = 1  //اعاشة
        ]);
        MonitorOrderSector::create([
            'monitor_id'=>3,
            'order_sector_id'=>2, //sector = 1 //توريد
        ]);
        MonitorOrderSector::create([
            'monitor_id'=>3,
            'order_sector_id'=>3, //sector = 3 //صيانة
        ]);
        MonitorOrderSector::create([
            'monitor_id'=>3,
            'order_sector_id'=>5, //sector = 2 //اعاشة
        ]);
        MonitorOrderSector::create([
            'monitor_id'=>3,
            'order_sector_id'=>7, //sector = 4 //توريد
        ]);
    }
}
