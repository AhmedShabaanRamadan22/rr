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
        DB::table('roles')->insert([
            'name' => 'government',
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $permissions = ['view_meals_dashboard','view_all_meals_dashboard'];

        $govRole = DB::table('roles')->where('name', 'government')->first();
    
            if ($govRole) {
                $govRoleId = $govRole->id;

                foreach ($permissions as $permission) {
                    $permissionRecord = DB::table('permissions')->where('name', $permission)->first();

                    if ($permissionRecord) {
                        $exists = DB::table('role_has_permissions')
                            ->where('role_id', $govRoleId)
                            ->where('permission_id', $permissionRecord->id)
                            ->exists();

                        if (!$exists) {
                            DB::table('role_has_permissions')->insert([
                                'role_id' => $govRoleId,
                                'permission_id' => $permissionRecord->id,
                            ]);
                        }
                    }
                }
            }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            //
        });
    }
};
