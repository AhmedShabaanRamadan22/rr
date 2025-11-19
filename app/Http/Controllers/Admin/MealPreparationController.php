<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FoodWeight;
use App\Models\Meal;
use App\Models\Menu;
use App\Models\Nationality;
use App\Models\NationalityOrganization;
use App\Models\Organization;
use App\Models\OrganizationStage;
use App\Models\Sector;
use App\Models\StageBank;
use Illuminate\Http\Request;

class MealPreparationController extends Controller
{
    public function show($organization_id)
    {
        $data = $this->showData($organization_id);

        return view('admin.organizations.meal-preparation',$data);
    }

    public static function showData($organization_id){
        $pageTitle =  trans('translation.meal-preparation');
        $organization = Organization::with(
            'classifications.sectors:id,label,classification_id,nationality_organization_id',
            'classifications.sectors.nationality_organization.nationality:id,name,flag',
            'classifications.sectors.order_sectors:id,order_id,sector_id,parent_id',
            'classifications.sectors.order_sectors.order:id,organization_service_id,facility_id,user_id',
            'nationality_organizations:id,nationality_id,organization_id',
            'nationality_organizations.nationality:id,name,flag',
            'reason_dangers:id,operation_type_id,reason_id,danger_id,organization_id',
            'reason_dangers.reason:id,name',
            'district:id,name_ar,name_en',
            'city:id,name_ar,name_en',
            'organization_services:id,organization_id,service_id',
            'organization_services.orders:id,organization_service_id,facility_id',
            'organization_services.orders.facility:id,name'
        )->findOrFail($organization_id);

        $meals = Meal::whereHas('meal_organization_stages.organization_stage', function ($q) use ($organization) {
            $q->where('organization_id', $organization->id);
        });
        $stages = StageBank::whereDoesntHave('organization_stages', function ($q) use ($organization) {
            $q->where('organization_id', $organization->id);
        })->orderBy('arrangement')->get();

        $sectorJson = Sector::whereHas('classification', function ($q) use ($organization) {
            $q->where('organization_id', $organization->id);
        })->get();
        $menu = Menu::with('nationality_organization.organization', 'nationality_organization.food_weights')->whereHas('nationality_organization', function ($q) use ($organization) {
            $q->where('organization_id', $organization->id);
        })->get();

        $operations = [
            'Meals' => $meals->get(),
        ];
        $columnOptions = Organization::columnOptions($organization);

        $meal_columns = Meal::columnNames();
        $columnMeals = Meal::columnInputs();
        $subtextOptionMeals = Meal::columnSubtextOptions($organization, 'nationality_organization.nationality.name');
        $optionMeals = Meal::columnOptions($organization);
        $meal_subtext_options = Meal::columnSubtextOptions($organization);

        $foodWeightColumnOptions = FoodWeight::columnOptions($organization);
        $foodWeightColumns = FoodWeight::columnNames();
        $foodWeightColumnInputs = FoodWeight::columnInputs();

        $nationalities = Nationality::all();
        $nationalities_organization_columns = NationalityOrganization::columnNames();

        $organization_stages_columns = OrganizationStage::columnNames();

        return compact(
            'organization',
            'pageTitle',
            'columnOptions',
            'columnMeals',
            'optionMeals',
            'subtextOptionMeals',
            'stages',
            'foodWeightColumns',
            'foodWeightColumnOptions',
            'foodWeightColumnInputs',
            'nationalities',
            'nationalities_organization_columns',
            'organization_stages_columns',
            'meal_columns',
            'meal_subtext_options',
            'menu',
            'sectorJson',
        );
    }
}
