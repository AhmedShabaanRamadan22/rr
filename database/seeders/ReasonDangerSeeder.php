<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReasonDanger;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ReasonDangerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //TODO ORGANIZATION ID = 1

        ReasonDanger::create([ //1 ticket - kiroseen - medium
            "danger_id"=> 2,
            "reason_id"=> 1,
            "operation_type_id"=> 1,
            "organization_id"=> 1,
        ]);

        ReasonDanger::create([ //2 ticket - fire - high
            "danger_id"=> 1,
            "reason_id"=> 2,
            "operation_type_id"=> 1,
            "organization_id"=> 1,
        ]);

        ReasonDanger::create([ //3 ticket - electricity - low
            "danger_id"=> 3,
            "reason_id"=> 3,
            "operation_type_id"=> 1,
            "organization_id"=> 1,
        ]);

        ReasonDanger::create([ //4 support food - kiroseen
            "danger_id"=> 4,
            "reason_id"=> 1,
            "operation_type_id"=> 2,
            "organization_id"=> 1,
        ]);

        ReasonDanger::create([ //5 support food - fire
            "danger_id"=> 4,
            "reason_id"=> 2,
            "operation_type_id"=> 2,
            "organization_id"=> 1,
        ]);

        ReasonDanger::create([ //6 support food - electricity
            "danger_id"=> 4,
            "reason_id"=> 3,
            "operation_type_id"=> 2,
            "organization_id"=> 1,
        ]);

        ReasonDanger::create([ //7 support food - no staff
            "danger_id"=> 4,
            "reason_id"=> 4,
            "operation_type_id"=> 2,
            "organization_id"=> 1,
        ]);

        ReasonDanger::create([ //8 support food - low in stock
            "danger_id"=> 4,
            "reason_id"=> 5,
            "operation_type_id"=> 2,
            "organization_id"=> 1,
        ]);

        ReasonDanger::create([ //9 support water - no staff
            "danger_id"=> 4,
            "reason_id"=> 4,
            "operation_type_id"=> 3,
            "organization_id"=> 1,
        ]);

        ReasonDanger::create([ //10 support water - low in stock
            "danger_id"=> 4,
            "reason_id"=> 5,
            "operation_type_id"=> 3,
            "organization_id"=> 1,
        ]);

        ReasonDanger::create([ //11 support water - miss use
            "danger_id"=> 4,
            "reason_id"=> 6,
            "operation_type_id"=> 3,
            "organization_id"=> 1,
        ]);

        ///TODO ORGANIZATION ID = 2

        ReasonDanger::create([ //12 ticket - kiroseen - high
            "danger_id"=> 1,
            "reason_id"=> 1,
            "operation_type_id"=> 1,
            "organization_id"=> 2,
        ]);

        ReasonDanger::create([ //13 ticket - fire - medium
            "danger_id"=> 2,
            "reason_id"=> 2,
            "operation_type_id"=> 1,
            "organization_id"=> 2,
        ]);

        ReasonDanger::create([ //14 ticket - electricity - high
            "danger_id"=> 1,
            "reason_id"=> 3,
            "operation_type_id"=> 1,
            "organization_id"=> 2,
        ]);

        ReasonDanger::create([ //15 support food - kiroseen
            "danger_id"=> 4,
            "reason_id"=> 1,
            "operation_type_id"=> 2,
            "organization_id"=> 2,
        ]);

        ReasonDanger::create([ //16 support food - fire
            "danger_id"=> 4,
            "reason_id"=> 2,
            "operation_type_id"=> 2,
            "organization_id"=> 2,
        ]);

        ReasonDanger::create([ //17 support food - electricity
            "danger_id"=> 4,
            "reason_id"=> 3,
            "operation_type_id"=> 2,
            "organization_id"=> 2,
        ]);

        ReasonDanger::create([ //18 support food - no staff
            "danger_id"=> 4,
            "reason_id"=> 4,
            "operation_type_id"=> 2,
            "organization_id"=> 2,
        ]);

        ReasonDanger::create([ //19 support water - low in stock
            "danger_id"=> 4,
            "reason_id"=> 5,
            "operation_type_id"=> 3,
            "organization_id"=> 2,
        ]);

        ReasonDanger::create([ //20 support water - miss use
            "danger_id"=> 4,
            "reason_id"=> 6,
            "operation_type_id"=> 3,
            "organization_id"=> 2,
        ]);

        
    
    


    }
}

