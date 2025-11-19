<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $organization_ids = [1,2,3,4,5,6,7];

        foreach ($organization_ids as  $organization_id) {
            $exists = DB::table('assist_questions')->where('organization_id', $organization_id)->exists();
            $orgExist = DB::table('organizations')->where('id',$organization_id)->exists();
            if (!$exists && $orgExist) {
                DB::table('assist_questions')->insert([
                    'organization_id' => $organization_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
