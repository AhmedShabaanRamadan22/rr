<?php

namespace Database\Seeders;

use App\Models\Sender;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sender::create([
            'name'=>'ضيوف البيت',
            'email'=>'no-replay@albaitguests-dev.rmcc.sa',
            'phone_app_sid'=>'d5XaaSqmEjlCGDj3yxeeNCyoUz6PkN',
            'phone_sender_id'=>'ALBAITGUEST',
            'whatsapp_instance_id' => 'instance71376',
            'whatsapp_token' => '8247d6es3ib9hfpy',
        ]);
        
        Sender::create([
            'name'=>'افريقيا',
            'email'=>'no-replay@africa-dev.rmcc.sa',
            'phone_app_sid'=>'j309Js01vRZTAxleJC7SDnWQbQkCFW',
            'phone_sender_id'=>'RAKAYA',
            'whatsapp_instance_id' => 'instance69319',
            'whatsapp_token' => 'om7h2t171ory4n6m',
        ]);

        Sender::create([
            'name'=>'محلي',
            'email'=>'no-replay@test-dev.rmcc.sa',
            'phone_app_sid'=>'',
            'phone_sender_id'=>'',
            'whatsapp_instance_id' => 'instance71373',
            'whatsapp_token' => '8247d6es3ib9hfdf',
        ]);
    }
}
