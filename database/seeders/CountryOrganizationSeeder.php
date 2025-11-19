<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CountryOrganization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CountryOrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CountryOrganization::create([//1
            'organization_id'=> '1',
            'country_id' => '3', 
        ]);
        CountryOrganization::create([//2
            'organization_id'=> '1',
            'country_id' => '100', 
        ]);
        CountryOrganization::create([//3
            'organization_id'=> '1',
            'country_id' => '124', 
        ]);
        CountryOrganization::create([//4
            'organization_id'=> '1',
            'country_id' => '129', 
        ]);
        CountryOrganization::create([//5
            'organization_id'=> '1',
            'country_id' => '157', 
        ]);
        CountryOrganization::create([//6
            'organization_id'=> '1',
            'country_id' => '176', 
        ]);
        CountryOrganization::create([//7
            'organization_id'=> '1',
            'country_id' => '177', 
        ]);

        
        CountryOrganization::create([//8
            'organization_id'=> '2',
            'country_id' => '204', 
        ]);
        CountryOrganization::create([//9
            'organization_id'=> '2',
            'country_id' => '205', 
        ]);
        CountryOrganization::create([//10
            'organization_id'=> '2',
            'country_id' => '228', 
        ]);
        CountryOrganization::create([//11
            'organization_id'=> '2',
            'country_id' => '243', 
        ]);
        CountryOrganization::create([//12
            'organization_id'=> '2',
            'country_id' => '246', 
        ]);
        CountryOrganization::create([//13
            'organization_id'=> '2',
            'country_id' => '35', 
        ]);
        

        CountryOrganization::create([//14
            'organization_id'=> '3',
            'country_id' => '2', 
        ]);
        CountryOrganization::create([//15
            'organization_id'=> '3',
            'country_id' => '24', 
        ]);
        CountryOrganization::create([//16
            'organization_id'=> '3',
            'country_id' => '122', 
        ]);
        CountryOrganization::create([//17
            'organization_id'=> '3',
            'country_id' => '171', 
        ]);
        CountryOrganization::create([//18
            'organization_id'=> '3',
            'country_id' => '186', 
        ]);
        CountryOrganization::create([//19
            'organization_id'=> '3',
            'country_id' => '192', 
        ]);
    }
}
