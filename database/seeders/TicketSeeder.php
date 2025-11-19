<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Ticket::create([
            'reason_danger_id' => 1,
            'user_id' => 5,
            'status_id' => 8,
            'order_sector_id' => 1,
        ]);
        Ticket::create([
            'reason_danger_id' => 2,
            'user_id' => 1,
            'status_id' => 9,
            'order_sector_id' => 5,
        ]);

        Ticket::create([
            'reason_danger_id' => 12,
            'user_id' => 1,
            'status_id' => 8,
            'order_sector_id' => 7,
        ]);

        Ticket::create([
            'reason_danger_id' => 3,
            'user_id' => 5,
            'status_id' => 9,
            'order_sector_id' => 2,
        ]);

        Ticket::create([
            'reason_danger_id' => 2,
            'user_id' => 3,
            'status_id' => 9,
            'order_sector_id' => 3,
        ]);
    }
}