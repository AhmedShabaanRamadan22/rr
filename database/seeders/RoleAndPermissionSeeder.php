<?php

namespace Database\Seeders;

use DB;
use Hash;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\OrganizationUser;
// use Spatie\Permission\Models\Role;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();


        //for roles
        $superadminRole = Role::create(["name" => "superadmin"]);
        Role::create(["name" => "admin"]);
        Role::create(["name" => "organization employee"]);
        Role::create(["name" => "organization admin"]);
        Role::create(["name" => "employee"]);
        Role::create(["name" => "monitor"]);
        Role::create(["name" => "supervisor"]);
        Role::create(["name" => "boss"]);
        Role::create(["name" => "providor"]);
        Role::create(["name" => "sector manager"]);
        Role::create(["name" => "organization chairman"]);
        Role::create(["name" => "assistant representer"]);


        //test permission
        // Permission::create(['name'=> 'view']);
        // Permission::create(['name'=> 'add']);
        // Permission::create(['name'=> 'edit']);
        // Permission::create(['name'=> 'delete']);

        // Permissions from system actions file and import from sql
        $path = base_path() . '/database/data/sql/permissions.sql';
        DB::unprepared(file_get_contents($path));
        // last index of permissions 354


        $osama = User::create([ //4
            'name' => 'مدير النظام',
            'email' => 'superadmin@admin.com',
            'phone' => '570044066',
            'phone_code' => '+966',
            'password' => Hash::make('password'),
            'national_id' => '1111111118',
            'organization_id' => '3',
            'national_source' => '6',
            'nationality' => '192',
            'email_verified_at' => now(),
            'verified_at' => now(),
            'national_id_expired_hj' => '1446-09-29',
            'national_id_expired' => '2024-09-29',
            'birthday' => '1999-07-29',
            'birthday_hj' => '1420-02-29',
            // 'bravo_id' => '4',
        ]);

        //make user admin and assign it's role to superadmin
        // $osama = User::create([
        //     'name' => 'Super Admin',
        //     'email' => 'superadmin@admin.com',
        //     'phone' => '570044066',
        //     'phone_code' => '+966',
        //     'password' => Hash::make('password'),
        //     'national_id' => '1234567890',
        //     'nationality' => '192',
        //     'email_verified_at' => now(),
        //     'verified_at' => now(),
        // ]);

        if (!config('app.only_important_seeder_flag')) {
            $reem = User::create([ //1
                'name' => 'ريم العتمي',
                'email' => 'user2@user.com',
                'phone' => '557436279',
                'phone_code' => '+966',
                'password' => Hash::make('password'),
                'national_id' => '2150843304',
                'organization_id' => '1',
                'national_source' => '6',
                'nationality' => '192',
                'email_verified_at' => now(),
                'verified_at' => now(),
                'national_id_expired_hj' => '1446-04-29',
                'national_id_expired' => '2026-04-29',
                'birthday' => '1999-07-29',
                'birthday_hj' => '1420-02-29',
            ]);
            $ghaidaa = User::create([ //2
                'name' => 'غيداء المغربي',
                'email' => 'user4@user.com',
                'phone' => '530410927',
                'phone_code' => '+966',
                'password' => Hash::make('password'),
                'national_id' => '1101078960',
                'organization_id' => '1',
                'national_source' => '6',
                'nationality' => '192',
                'email_verified_at' => now(),
                'verified_at' => now(),
                'national_id_expired_hj' => '1446-01-29',
                'national_id_expired' => '2024-01-29',
                'birthday' => '1999-07-29',
                'birthday_hj' => '1420-02-29',
            ]);

            $jawad = User::create([ //3
                'name' => 'جواد الغريبي',
                'email' => 'user6@user.com',
                'phone' => '596938018',
                'phone_code' => '+966',
                'password' => Hash::make('password'),
                'national_id' => '1111111117',
                'organization_id' => '1',
                'national_source' => '6',
                'nationality' => '192',
                'email_verified_at' => now(),
                'verified_at' => now(),
                'national_id_expired_hj' => '1446-03-29',
                'national_id_expired' => '2025-03-29',
                'birthday' => '1999-07-29',
                'birthday_hj' => '1420-02-29',
            ]);


            //seed the DB bravo organization
            $this->call([BravoSeeder::class]);

            $omar = User::create([ //5
                'name' => 'عمر خان',
                'email' => 'okhan@refada.com',
                'phone' => '565603434',
                'phone_code' => '+966',
                'password' => Hash::make('password'),
                'national_id' => '2146036682',
                'organization_id' => '1',
                'national_source' => '6',
                'nationality' => '192',
                'email_verified_at' => now(),
                'verified_at' => now(),
                'national_id_expired_hj' => '1446-12-29',
                'national_id_expired' => '2024-12-29',
                'birthday' => '1999-08-29',
                'birthday_hj' => '1420-02-29',
                'bravo_id' => '5',
            ]);

            $lama = User::create([ //6
                'name' => 'لمى بوقس',
                'email' => 'org@org.com',
                'phone' => '542187024',
                'phone_code' => '+966',
                'password' => Hash::make('password'),
                'national_id' => '1111111121',
                'organization_id' => '1',
                'national_source' => '6',
                'nationality' => '192',
                'email_verified_at' => now(),
                'verified_at' => now(),
                'national_id_expired_hj' => '1446-10-29',
                'national_id_expired' => '2025-10-29',
                'birthday' => '1999-08-29',
                'birthday_hj' => '1420-02-29',
                'bravo_id' => '1',
            ]);

            $mohammed = User::create([ //7
                'name' => 'محمد الأحمر',
                'email' => 'user8@user.com',
                'phone' => '565628065',
                'phone_code' => '+966',
                'password' => Hash::make('password'),
                'national_id' => '1111111119',
                'organization_id' => '1',
                'national_source' => '6',
                'nationality' => '192',
                'email_verified_at' => now(),
                'verified_at' => now(),
                'national_id_expired_hj' => '1446-11-29',
                'national_id_expired' => '2025-11-29',
                'birthday' => '1999-07-29',
                'birthday_hj' => '1420-02-29',
                'bravo_id' => '2',
            ]);


            $ruba = User::create([ //8
                'name' => 'ربى بوقس',
                'email' => 'user3@user.com',
                'phone' => '542187064',
                'phone_code' => '+966',
                'password' => Hash::make('password'),
                'national_id' => '1098079484',
                'organization_id' => '1',
                'national_source' => '6',
                'nationality' => '192',
                'email_verified_at' => now(),
                'verified_at' => now(),
                'national_id_expired_hj' => '1446-11-29',
                'national_id_expired' => '2026-11-29',
                'birthday' => '1999-08-29',
                'birthday_hj' => '1420-02-29',
                'bravo_id' => '3',
            ]);

            $ensaf = User::create([ //9
                'name' => 'إنصاف السبحي',
                'email' => 'user5@user.com',
                'phone' => '555049374',
                'phone_code' => '+966',
                'password' => Hash::make('password'),
                'national_id' => '1111111116',
                'organization_id' => '1',
                'national_source' => '6',
                'nationality' => '192',
                'email_verified_at' => now(),
                'verified_at' => now(),
                'national_id_expired_hj' => '1446-02-29',
                'national_id_expired' => '2024-02-29',
                'birthday' => '1999-08-29',
                'birthday_hj' => '1420-02-29',
            ]);

            $sami = User::create([ //10
                'name' => 'سامي',
                'email' => 'sami@user.com',
                'phone' => '531065595',
                'phone_code' => '+966',
                'password' => Hash::make('password'),
                'national_id' => '1111111212',
                'organization_id' => '1',
                'national_source' => '6',
                'nationality' => '192',
                'email_verified_at' => now(),
                'verified_at' => now(),
                'national_id_expired_hj' => '1446-02-29',
                'national_id_expired' => '2024-02-29',
                'birthday' => '1999-08-29',
                'birthday_hj' => '1420-02-29',
            ]);

            $rana = User::create([ //11
                'name' => 'رنا',
                'email' => 'rana@user.com',
                'phone' => '560409930',
                'phone_code' => '+966',
                'password' => Hash::make('password'),
                'national_id' => '1111141219',
                'organization_id' => '1',
                'national_source' => '6',
                'nationality' => '192',
                'email_verified_at' => now(),
                'verified_at' => now(),
                'national_id_expired_hj' => '1446-02-29',
                'national_id_expired' => '2024-02-29',
                'birthday' => '1999-08-29',
                'birthday_hj' => '1420-02-29',
            ]);

            $rania = User::create([ //12
                'name' => 'رانيا',
                'email' => 'rania@user.com',
                'phone' => '561020082',
                'phone_code' => '+966',
                'password' => Hash::make('password'),
                'national_id' => '1111111122',
                'organization_id' => '1',
                'national_source' => '6',
                'nationality' => '192',
                'email_verified_at' => now(),
                'verified_at' => now(),
                'national_id_expired_hj' => '1446-02-29',
                'national_id_expired' => '2024-02-29',
                'birthday' => '1990-09-29',
                'birthday_hj' => '1409-02-29',
            ]);


            $reem->assignRole(['9']); //
            $rana->assignRole(['11']); //
            $rania->assignRole(['11']); //
            $rana->assignRole(['10']); //
            $sami->assignRole(['10']); //
            $sami->assignRole(['12']); //
            $ensaf->assignRole(['10']); //
            $lama->assignRole(['10']); //
            $ruba->assignRole(['10']);
            $sami->assignRole(['11']); //
            $reem->assignRole(['6']); //
            $lama->assignRole(['2']); //
            $lama->assignRole(['11']); //
            $lama->assignRole(['9']); //
            $lama->assignRole(['12']); //
            $ghaidaa->assignRole(['9']);
            $ghaidaa->assignRole(['12']);
            $mohammed->assignRole(['5']); //
            $jawad->assignRole(['6']);
            $jawad->assignRole(['9']);
            $ensaf->assignRole(['4']);
            $ruba->assignRole(['7']);
            $ruba->assignRole(['9']);
            $omar->assignRole(['6']);
            $omar->assignRole(['9']);



            OrganizationUser::create(['user_id' => 1, 'organization_id' => 1]);
            OrganizationUser::create(['user_id' => 12, 'organization_id' => 1]);
            OrganizationUser::create(['user_id' => 2, 'organization_id' => 1]);
            OrganizationUser::create(['user_id' => 3, 'organization_id' => 2]);
            OrganizationUser::create(['user_id' => 3, 'organization_id' => 1]);
            OrganizationUser::create(['user_id' => 1, 'organization_id' => 2]);
            OrganizationUser::create(['user_id' => 10, 'organization_id' => 2]);
            OrganizationUser::create(['user_id' => 5, 'organization_id' => 1]);
            $rakaya_chairman = User::create([ //13
                'name' => 'رئيس ركايا',
                'email' => 'rakaya@rakaya.co',
                'phone' => '570044066',
                'phone_code' => '+966',
                'password' => Hash::make('password'),
                'national_id' => '1111115122',
                'organization_id' => '1',
                'national_source' => '6',
                'nationality' => '192',
                'email_verified_at' => now(),
                'verified_at' => now(),
                'national_id_expired_hj' => '1446-02-29',
                'national_id_expired' => '2024-02-29',
                'birthday' => '1990-09-29',
                'birthday_hj' => '1409-02-29',
            ]);
            $rakaya_chairman->assignRole(['11']);
        }



        $osama->assignRole(['1']); //
        $osama->assignRole(['10']);
        $osama->givePermissionTo(Permission::all());
        $superadminRole->givePermissionTo(Permission::all());

        // OrganizationUser::create(['user_id' => $osama->id, 'organization_id' => 3]);
        // OrganizationUser::create(['user_id' => $osama->id, 'organization_id' => 2]);
        // OrganizationUser::create(['user_id' => $osama->id, 'organization_id' => 1]);
    }
}
