<?php

namespace App\Http\Controllers\Admin;

use App\Models\Fine;
use App\Models\Meal;
use App\Models\Role;
use App\Models\User;
use App\Models\Danger;
use App\Models\Reason;
use App\Models\Sector;
use App\Models\Ticket;
use App\Models\Service;
use App\Models\Support;
use App\Models\Category;
use App\Traits\SmsTrait;
use App\Models\StageBank;
use App\Models\Dictionary;
use App\Models\FoodWeight;
use App\Models\Organization;
use App\Models\ReasonDanger;
use Illuminate\Http\Request;
use App\Models\OperationType;
use App\Models\SubmittedForm;
use App\Traits\WhatsappTrait;
use App\Models\Classification;
use App\Models\AttachmentLabel;
use App\Traits\AttachmentTrait;
use App\Models\ContractTemplate;
use App\Models\FineOrganization;
use App\Models\OrganizationStage;
use App\Http\Controllers\Controller;
use App\Models\OrganizationCategory;
use App\Models\MealOrganizationStage;
use App\Models\NationalityOrganization;
use App\Models\QuestionBankOrganization;
use App\Http\Requests\OrganizationRequest;
use App\Models\Menu;
use App\Models\Nationality;
use App\Services\CloudflareService;
use PDO;

class OrganizationController extends Controller
{
  use AttachmentTrait, WhatsappTrait, SmsTrait;

    public function __construct(
    private CloudflareService $cloudflare_service,
  ) {}

  public function index()
  {
    $organizations = Organization::all();
    $services = Service::all();
//    $reason_dangers = ReasonDanger::all();

    return view('admin.organizations.index', compact('organizations', 'services'));
  }
  //??=========================================================================================================
  public function store(Request $request)
  {
    //!! need to custom OrganizationStoreRequest.php
    $organizations = Organization::where(['name_ar' => $request->name_ar])
      ->orWhere(['name_en' => $request->name_en])
      ->orWhere(['domain' => $request->domain])
      ->first();
    if ($organizations) {
      return back()->with(array('message' => trans('translation.organization-exists'), 'alert-type' => 'error'));
    }
    $new_organizations = Organization::create($request->only('name_ar', 'name_en', 'domain'));

    $category = Category::where(['code' => 'OTHER'])->first();
    OrganizationCategory::create(['category_id' => $category->id, 'organization_id' => $new_organizations->id]);

    $user = Auth()->user();
    $message = trans('translation.send-whatsapp-add-new-organization', ['user' => $user->name, 'organization_name' => $new_organizations->name]);
    $whatsapp_response = $this->send_message($new_organizations->sender, $message, $user->phone_code . $user->phone);
    $sending_sms = $this->send_sms($new_organizations->sender, $message, $user->phone, $user->phone_code);
    return back()->with(array('message' => trans('translation.Added successfully'), 'alert-type' => 'success'));
  }
  //??=========================================================================================================
  public function edit(Organization $organization)
  {
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
    )->findOrFail($organization->id);

    // $services = Service::whereDoesntHave('organizations', function ($query) use ($organization) {
    //     $query->where('organization_id', $organization->id);
    // })->get();
    //statistics for each operation
    $meals = Meal::whereHas('meal_organization_stages.organization_stage', function ($q) use ($organization) {
      $q->where('organization_id', $organization->id);
    });
    $tickets = Ticket::whereHas('order_sector.sector.classification', function ($q) use ($organization) {
      $q->where('organization_id', $organization->id);
    });
    $supports = Support::whereHas('order_sector.sector.classification', function ($q) use ($organization) {
      $q->where('organization_id', $organization->id);
    });
    $fines = Fine::whereHas('order_sector.sector.classification', function ($q) use ($organization) {
      $q->where('organization_id', $organization->id);
    });
    $submitted_forms = SubmittedForm::whereHas('order_sector.sector.classification', function ($q) use ($organization) {
      $q->where('organization_id', $organization->id);
    });
    $operations = [
      'Meals' => $meals->get(),
      'Tickets' => $tickets->get(),
      'Supports' => $supports->get(),
      'Fines' => $fines->get(),
      'Submitted_forms' => $submitted_forms->get()
    ];

    // dd($operations);


    // ?? commented to optimize edit organization page
    // $districts = District::all();
    // $services = Service::all();
    // $columnCountries = CountryOrganization::columnInputs();
    // $optionCountries = CountryOrganization::columnOptions($organization);
    // $employee_contract_columns = User::contractsColumnNames();
    // $user_contract_columns = User::columnInputs();
    // $userColumnOptions = User::columnOptions();
    // $user_dictionaries = Dictionary::where('type', 'users')->get();

    $nationalities = Nationality::all();
    $optionReasonDanger = ReasonDanger::columnOptions();
    $questions_bank_options = QuestionBankOrganization::columnOptions();
    // $foodWeightColumnOptions = FoodWeight::columnOptions($organization);
    $foodWeightColumnOptions = null;
    $optionSectors = Sector::columnOptions($organization);
    // $optionMeals = Meal::columnOptions($organization);
    $optionMeals = null;
    $optionTickets = Ticket::columnOptions($organization);
    $fine_organization_options = FineOrganization::columnOptions($organization);
    $roles = Role::all();
    $stages = null;
    // $stages = StageBank::whereDoesntHave('organization_stages', function ($q) use ($organization) {
    //   $q->where('organization_id', $organization->id);
    // })->orderBy('arrangement')->get();
    $reason_dangers = ReasonDanger::all();
    $operation_types = OperationType::all();
    $columnReasonDanger = ReasonDanger::columnInputs();
    $columnSectors = Sector::columnInputs();
    $columnTickets = Ticket::columnInputs($organization);
    $subtextOptionTickets = Ticket::columnSubtextOptions($organization);
    $columnInputSupports = Support::columnInputs();
    $columnFoodSupports = Support::columnOptions($organization, OperationType::FOOD_SUPPORT);
    $columnWaterSupports = Support::columnOptions($organization, OperationType::WATER_SUPPORT);
    $subtextOptionSupports = Support::columnSubtextOptions($organization);
    $columnClassifications = Classification::columnInputs();
    $meal_columns = null;
    // $meal_columns = Meal::columnNames();
    $subtextOptionSectors = Sector::columnSubtextOptions($organization, 'guest_value_sar');
    // $foodWeightColumns = FoodWeight::columnNames();
    // $foodWeightColumnInputs = FoodWeight::columnInputs();
    $foodWeightColumns = null;
    $foodWeightColumnInputs =null;
    // $columnMeals = Meal::columnInputs();
    $columnMeals = null;
    $subtextOptionMeals =null;
    // $subtextOptionMeals = Meal::columnSubtextOptions($organization, 'nationality_organization.nationality.name');
    // $mealsJson = $organization->mealsJson();
    $mealsJson = null;
    $allReason = Reason::all();
    $columnOptions = Organization::columnOptions($organization);
    $sector_columns = Sector::columnNames();
    $ticket_columns = Ticket::columnNames();
    // $nationalities_organization_columns = NationalityOrganization::columnNames();
    $nationalities_organization_columns = null;
    $order_sector_dictionaries = Dictionary::where('type', 'order_sectors')->get();
    $dangers = Danger::all();
    $fineJson = FineOrganization::columnOptions()['fine_bank_id'];
    $questions_bank_columns = QuestionBankOrganization::columnNames();
    $questions_bank_inputs = QuestionBankOrganization::columnInputs();
    $fine_columns = Fine::columnNames($organization);
    $support_columns = Support::columnNames($organization);
    $fine_organization_columns = FineOrganization::columnNames();
    $fine_organization_inputs = FineOrganization::columnInputs();
    // $organization_stages_columns = OrganizationStage::columnNames();
    $organization_stages_columns = null;
    $organization_stages_inputs = null;
    $meal_organization_stages_columns = null;
    // $organization_stages_inputs = OrganizationStage::columnInputs();
    // $meal_organization_stages_columns = MealOrganizationStage::columnNames();
    $categories = Category::all();
    $sectorJson = Sector::whereHas('classification', function($q) use ($organization){
      $q->where('organization_id', $organization->id);
    })->get();
    $menu = null;
    // $menu = Menu::with('nationality_organization.organization', 'nationality_organization.food_weights')->whereHas('nationality_organization', function($q) use ($organization){
    //   $q->where('organization_id', $organization->id);
    // })->get();
    $ticket_attachment = AttachmentLabel::find(AttachmentLabel::TICKET_LABEL);
    $support_attachment = AttachmentLabel::find(AttachmentLabel::SUPPORT_LABEL);
    $sector_attachment = AttachmentLabel::find(AttachmentLabel::SECTOR_SIGHT_LABEL);
    $ticket_subtext_columns = Ticket::columnSubtextOptions( $organization );
    $support_subtext_columns = Support::columnSubtextOptions( $organization );
    // $meal_subtext_options = Meal::columnSubtextOptions($organization);
    $meal_subtext_options = null;

      return view('admin.organizations.edit', compact(
      'organization',
      'menu',
      'sectorJson',
      'columnSectors',
      'foodWeightColumns',
      'foodWeightColumnInputs',
      'categories',
      'columnMeals',
      'meal_columns',
      'subtextOptionMeals',
      'columnClassifications',
      'meal_subtext_options',
      'support_columns',
      'mealsJson',
      'roles',
      'columnOptions',
      'sector_columns',
      'order_sector_dictionaries',
      'columnTickets',
      'subtextOptionTickets',
      'questions_bank_columns',
      'questions_bank_inputs',
      'fine_columns',
      'fine_organization_columns',
      'fine_organization_inputs',
      'fineJson',
      'columnReasonDanger',
      'subtextOptionSectors',
      'operation_types',
      'dangers',
      'meal_columns',
      'columnWaterSupports',
      'columnFoodSupports',
      'columnInputSupports',
      'subtextOptionSupports',
      'allReason',
      'ticket_columns',
      'nationalities_organization_columns',
      'organization_stages_columns',
      'organization_stages_inputs',
      'stages',
      'meal_organization_stages_columns',
      'operations',
      'nationalities',
      'optionReasonDanger',
      'questions_bank_options',
      'foodWeightColumnOptions',
      'optionSectors',
      'optionMeals',
      'optionTickets',
      'fine_organization_options',
      'reason_dangers',
      'ticket_attachment',
      'support_attachment',
      'sector_attachment',
      'ticket_subtext_columns',
      'support_subtext_columns',
      // ?? commented to optimize edit organization page
      // 'districts',
      // 'services',
      // 'columnCountries',
      // 'optionCountries',
      // 'employee_contract_columns',
      // 'user_contract_columns',
      // 'userColumnOptions',
      // 'user_dictionaries',
    ));
  }
  //??=========================================================================================================
  public function update(OrganizationRequest $request, Organization $organization)
  {
    // TODO: make update dynamic
    //if(!$request->rules()) return redirect()->route('organizations.index');
    // dd($request->all());
    // dd($organization->chairman);
    $organization->update($request->only([
      'name_ar',
      'name_en',
      'domain',
      'sender_id',
      'about_us',
      'policies',
      'phone',
      'has_esnad',
      'close_registeration',
      'close_order',
      'primary_color',
      'attachment_labels',
      'contract',
      'city_id',
      'district_id',
      'street_name',
      'postal_code',
      'building_number',
      'sub_number',
      'email',
      'release_date',
      'release_date_hj',
      'registration_number',
      'registration_source',
      'license_id',
    ]));

    if ($request->organization_chairman != null) {
      if ($organization->chairman != null) {
        $organization->chairman->update(['organization_id' => null]);
        $organization->chairman->removeRole(Role::ORGANIZATION_CHAIRMAN);
      }
      $user = User::find($request->organization_chairman);
      $user->update(['organization_id' => $organization->id]);
      $user->assignRole([Role::ORGANIZATION_CHAIRMAN]);
    }
    if ($request->has('logo')) {
      $this->update_attachment($request->logo, $organization, AttachmentLabel::LOGO_LABEL);
    }
    if ($request->has('background_image')) {
      $this->update_attachment($request->background_image, $organization, AttachmentLabel::BACKGROUND_LABEL);
    }
    if ($request->has('organization_profile')) {
      $this->update_attachment($request->organization_profile, $organization, AttachmentLabel::PROFILE_FILE_LABEL);
    }
    if ($request->has('type')) {
      if ($organization->contract_template($request->type) != null) {
        $organization->contract_template($request->type)->update($request->only('content'));
        return back()->with(array('message' => trans("translation.updated-successfully"), 'alert-type' => 'success'), 200);
      } else {
        ContractTemplate::create($request->only('organization_id', 'type', 'content'));
        return back()->with(array('message' => trans("translation.updated-successfully"), 'alert-type' => 'success'), 200);
      }
      // User::find($request->chairman_id)->update(['organization_id' => $organization->id]);
    }
    return back()->with(array('message' => trans("translation.updated-successfully"), 'alert-type' => 'success'), 200);
  }
  //??=========================================================================================================
  public function destroy(Organization $organization)
  {
    // check no organization service linked
    if ($organization->organization_services->isNotEmpty()) {
      return response()->json(['message' => trans('translation.Organization has services, please delete them first!'), 'alert-type' => 'error'], 400);
    }

    $organization->delete();

    // return response(['message' => 'Organization has deleted!'], 200);
    return response()->json(['message' => 'Organization was deleted successfuly!', 'alert-type' => 'success'], 200);
  }
  public function deletePortfolio(Request $request)
  {
    $organization = Organization::findOrFail($request->organization_id);
    if (isset($organization->profile_file)) {
      $organization->profile_attachment->delete();
      return response()->json(['message' => trans('translation.protfolio-deleted-successfully'), 'alert-type' => 'success'], 200);
    }
    return response()->json(['message' => trans('translation.something went wrong!'), 'alert-type' => 'error'], 400);
  }

  //??=========================================================================================================
  public function getCustomHostnameStatus(Organization $organization)
  {
    return $this->cloudflare_service->getCustomHostnameStatus($organization);
  }
}