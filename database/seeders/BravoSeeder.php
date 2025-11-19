<?php

namespace Database\Seeders;
use App\Models\Bravo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BravoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        Bravo::create([//1
            'number'=>'0912345',
            'code'=>'A',
            'organization_id'=>1,
            'given_id'=>2,
            'channel'=>'H10'
        ]);
        Bravo::create([//1
            'number'=>'0912346',
            'code'=>'A',
            'organization_id'=>2,
            'given_id'=>2,
            'channel'=>'H11'
        ]);
        Bravo::create([//1
            'number'=>'0912347',
            'code'=>'B',
            'organization_id'=>1,
            'given_id'=>1,
            'channel'=>'H10'
        ]);
        Bravo::create([//1
            'number'=>'0912348',
            'code'=>'B',
            'organization_id'=>2,
            'given_id'=>1,
            'channel'=>'H11'
        ]);
        Bravo::create([//1
            'number'=>'0912349',
            'code'=>'C',
            'organization_id'=>1,
            'given_id'=>2,
            'channel'=>'H10'
        ]);
        Bravo::create([//1
            'number'=>'0912344',
            'code'=>'C',
            'organization_id'=>3,
            'given_id'=>3,
            'channel'=>'H11'
        ]);
        Bravo::create([//1
            'number'=>'0912343',
            'code'=>'Z',
            'organization_id'=>3,
            'given_id'=>1,
            'channel'=>'H12'
        ]);
        Bravo::create([//1
            'number'=>'0912342',
            'code'=>'Z',
            'organization_id'=>1,
            'given_id'=>2,
            'channel'=>'H12'
        ]);
        Bravo::create([//1
            'number'=>'0912341',
            'code'=>'S',
            'organization_id'=>3,
            'given_id'=>1,
            'channel'=>'H12'
        ]);


    }
}
