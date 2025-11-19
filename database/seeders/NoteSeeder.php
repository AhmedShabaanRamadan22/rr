<?php

namespace Database\Seeders;

use App\Models\Note;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Note::create([
            "notable_id"=> "1",
            "notable_type"=> "App\Models\Ticket",
            "content"=> "الرجاد الاسراع في الرد",
            "user_id"=> "5",
        ]);
        Note::create([
            "notable_id"=> "1",
            "notable_type"=> "App\Models\Ticket",
            "content" => "تمت مراجعة الطلب والتواصل مع الجهة المختصة",
            "user_id"=> "1",
        ]);
        Note::create([
            "notable_id"=> "1",
            "notable_type"=> "App\Models\Ticket",
            "content" => "تم الرد من الجهة المختصة",
            "user_id"=> "1",
        ]);
        Note::create([
            "notable_id"=> "1",
            "notable_type"=> "App\Models\Ticket",
            "content" => "تم التواصل مع جهة اخرى",
            "user_id"=> "1",
        ]);
        Note::create([
            "notable_id"=> "1",
            "notable_type"=> "App\Models\Ticket",
            "content" => "يتم التواصل مع المراقب لنقص المرفقات",
            "user_id"=> "1",
        ]);
        Note::create([
            "notable_id"=> "1",
            "notable_type"=> "App\Models\Ticket",
            "content" => "تتم مراجعة المرفقات الخاصة بالبلاغ",
            "user_id"=> "1",
        ]);
        Note::create([
            "notable_id"=> "1",
            "notable_type"=> "App\Models\Ticket",
            "content" => "يتم التواصل مع الجهة المختصة لارسال الدعم",
            "user_id"=> "1",
        ]);
        Note::create([
            "notable_id"=> "1",
            "notable_type"=> "App\Models\Ticket",
            "content" => "يتم تنفيذ الطلب وارسال الدعم للموقع",
            "user_id"=> "1",
        ]);
    }
}