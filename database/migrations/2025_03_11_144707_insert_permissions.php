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
        Schema::table('permissions', function (Blueprint $table) {
            $permissions = [
                // 'view_tickets',
                // 'view_supports',
                // 'view_assists',
                // 'view_fines',
                'edit_ticket',
                'edit_support',
                'edit_assist',
                'edit_fines',
                // 'add_assist',
            ];
    
            // Insert permissions if they do not exist
            foreach ($permissions as $permission) {
                $exists = DB::table('permissions')->where('name', $permission)->exists();
                if (!$exists) {
                    DB::table('permissions')->insert([
                        'name' => $permission,
                        'guard_name' => 'web',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
    
            // Check if the 'admin' role exists
            $adminRole = DB::table('roles')->where('name', 'admin')->first();
    
            if ($adminRole) {
                $adminRoleId = $adminRole->id;
    
                foreach ($permissions as $permission) {
                    $permissionRecord = DB::table('permissions')->where('name', $permission)->first();
    
                    if ($permissionRecord) {
                        $exists = DB::table('role_has_permissions')
                            ->where('role_id', $adminRoleId)
                            ->where('permission_id', $permissionRecord->id)
                            ->exists();
    
                        if (!$exists) {
                            DB::table('role_has_permissions')->insert([
                                'role_id' => $adminRoleId,
                                'permission_id' => $permissionRecord->id,
                            ]);
                        }
                    }
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            //
        });
    }
};
