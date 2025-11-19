<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Fine;
use App\Models\FineOrganization;
use App\Models\Sender;
use App\Models\FoodType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */

    public function run(): void
    {
        // DB::statement("SET time_zone = 'Asia/Kuwait'");

        //seed the DB with Countries
        $this->call(CountrySeeder::class);

        //seed the DB with Countries
        $this->call(CitySeeder::class);

        //seed the DB with District
        $this->call([DistrictSeeder::class]);

        DB::statement("SET time_zone = '+03:00'");

        //seed the DB with statuses
        $this->call([StatusSeeder::class]);
        //seed the DB with organization
        $this->call([SenderSeeder::class]);

        //seed the DB with organization
        $this->call([OrganizationSeeder::class]);

        //seed the DB with role and permission
        $this->call([RoleAndPermissionSeeder::class]);

        if (!config('app.only_important_seeder_flag')) {
            //seed the DB with services
            $this->call([ServiceSeeder::class]);
        }
        //seed the DB with facility employee position
        $this->call([FacilityEmployeePositionSeeder::class]);

        if (!config('app.only_important_seeder_flag')) {
            //seed the DB with facilities
            $this->call([FacilitySeeder::class]);

            //seed the DB with facility employee
            $this->call([FacilityEmployeeSeeder::class]);

            //seed the DB with facilityServices
            $this->call([FacilityServicesSeeder::class]);

            //seed the DB with orders
            $this->call([OrderSeeder::class]);
        }

        //seed the DB with question types
        $this->call([QuestionTypeSeeder::class]);

        if (!config('app.only_important_seeder_flag')) {
            //seed the DB with regex
            $this->call([RegexSeeder::class]);

            //seed the DB with question bank
            $this->call([QuestionBankSeeder::class]);

            //seed the DB with question bank org
            $this->call([QuestionBankOrganizationSeeder::class]);

            //seed the DB with question
            $this->call([QuestionSeeder::class]);
        }

        //seed the DB with category
        $this->call([CategorySeeder::class]);

        if (!config('app.only_important_seeder_flag')) {
            //seed the DB with organizationCategory
            $this->call([OrganizationCategorySeeder::class]);

            //seed the DB with form
            $this->call([FormSeeder::class]);

            //seed the DB with Section
            $this->call([SectionSeeder::class]);

            //seed the DB with classifications
            $this->call([ClassificationSeeder::class]);

            //seed the DB with nationalities
            $this->call([NationalitiesSeeder::class]);

            //seed the DB with nationality organization
            $this->call([NationalityOrganizationSeeder::class]);

            //seed the DB with sector
            $this->call([SectorSeeder::class]);

            //seed the DB with monitor
            $this->call([MonitorSeeder::class]);

            //seed the DB with order sector
            $this->call([OrderSectorSeeder::class]);
        }

        //seed the DB with reason types
        $this->call([OperationTypeSeeder::class]);

        //seed the DB with period
        $this->call([PeriodSeeder::class]);

        // if (!config('app.only_important_seeder_flag')) {
            //seed the DB with danger
            $this->call([DangerSeeder::class]);

            //seed the DB with ticket reasons
            $this->call([ReasonSeeder::class]);
        // }

        //seed the DB with Attachments label
        $this->call([AttachmentLabelSeeder::class]);

        if (!config('app.only_important_seeder_flag')) {
            //seed the DB with Organization Attachment label
            $this->call([OrganizationAttachmentLabelSeeder::class]);

            //seed the DB with ticket reason danger
            $this->call([ReasonDangerSeeder::class]);

            //seed the DB with support
            $this->call([SupportSeeder::class]);

            //seed the DB with assist
            $this->call([AssistSeeder::class]);

            //seed the DB with ticket
            $this->call([TicketSeeder::class]);

            //seed the DB with monitor order sectors
            $this->call([MonitorOrderSectorSeeder::class]);

            //seed the DB with OrganizationNew
            $this->call([OrganizationNewSeeder::class]);

            //seed the DB with food type
            $this->call([FoodTypeSeeder::class]);

            //seed the DB with food
            $this->call([FoodSeeder::class]);

            //seed the DB with menu
            $this->call([MenuSeeder::class]);

            //seed the DB with Note
            $this->call([NoteSeeder::class]);

            //seed the DB with submitted forms
            $this->call([SubmittedFormSeeder::class]);

            //seed the DB with fine bank
            $this->call([FineBankSeeder::class]);

            //seed the DB with fine organization
            $this->call([FineOrganizationSeeder::class]);

        }

        //seed the DB with dictionaries
        $this->call([DictionarySeeder::class]);

        //seed the DB with StageBanks
        $this->call([StageBankSeeder::class]);

        //seed the DB with rakayas dep
        $this->call([DepartmentSeeder::class]);

        //seed the DB with banks
        $this->call([BankSeeder::class]);

        if (!config('app.only_important_seeder_flag')) {
            //seed the DB with iban
            $this->call([IbanSeeder::class]);
            //seed the DB with dictionaries
            // $this->call([DicSeeder::class]);

            //seed the DB country organization
            $this->call([CountryOrganizationSeeder::class]);
        }

        //seed the DB track location
        $this->call([TrackLoacationSeeder::class]);

        //seed the DB subject
        $this->call([SubjectSeeder::class]);

        //seed the DB interview standard
        $this->call([InterviewStandardSeeder::class]);



        /*
        // $admin = User::create([
        //     'name' => 'Admin',
        //     'email' => 'admin@admin.com',
        //     'phone' => '555555554',
        //     'phone_code' => '+966',
        //     'group_id' => 1,
        // ]);

        // User::create([
        //     'name' => 'User',
        //     'email' => 'user@user.com',
        //     'phone' => '555555551',
        //     'phone_code' => '+966',
        //     'group_id' => 1,
        // ]);

        // $superadmin->assignRole('superadmin');
        // $admin->assignRole('admin');
        */
    }
}
