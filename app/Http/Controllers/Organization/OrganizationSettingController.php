<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Admin\MealPreparationController;
use App\Http\Controllers\Controller;
use App\Models\AttachmentLabel;
use App\Models\Category;
use App\Models\Classification;
use App\Models\CountryOrganization;
use App\Models\Danger;
use App\Models\Dictionary;
use App\Models\District;
use App\Models\Fine;
use App\Models\FineOrganization;
use App\Models\Nationality;
use App\Models\OperationType;
use App\Models\Organization;
use App\Models\QuestionBankOrganization;
use App\Models\ReasonDanger;
use App\Models\Sector;
use App\Models\Service;
use App\Models\Support;
use App\Models\Ticket;
use Illuminate\Http\Request;

class OrganizationSettingController extends Controller
{
    public function show($organization_id){

        $organization = Organization::findOrFail($organization_id);
        return view('admin.organizations.settings.index',compact('organization'));
    }

    //=======================================================================================

    public function showVia($organization_id,$slug){

        if(method_exists($this,$slug)){
            return $this->{$slug}($organization_id);
        }
        return abort(404);
    }
    
    //=======================================================================================

    public function information($organization_id){
        $organization = Organization::findOrFail($organization_id);
        $columnOptions = Organization::columnOptions($organization);
        $districts = District::query()->get();
        return view('admin.organizations.settings.information',compact('organization','columnOptions','districts'));
        
    }
    
    //=======================================================================================
    
    public function services($organization_id) {
        
        $organization = Organization::findOrFail($organization_id);
        $services = Service::all();
        return view('admin.organizations.settings.services',compact('organization','services'));
    }
    
    //=======================================================================================
    
    public function reasonDangers($organization_id) {
        
        $organization = Organization::findOrFail($organization_id);
        $dangers = Danger::all();
        $operation_types = OperationType::all();
        $columnReasonDanger = ReasonDanger::columnInputs();
        $optionReasonDanger = ReasonDanger::columnOptions();
        
        return view('admin.organizations.settings.reason-dangers',compact('organization','dangers','operation_types','columnReasonDanger','optionReasonDanger'));
    }
    
    //=======================================================================================
    
    public function finesBank($organization_id) {
        
        $organization = Organization::findOrFail($organization_id);
        $fine_organization_columns = FineOrganization::columnNames();
        $fine_organization_inputs = FineOrganization::columnInputs();
        $fine_organization_options = FineOrganization::columnOptions($organization);
        $fineJson = FineOrganization::columnOptions()['fine_bank_id'];

        return view('admin.organizations.settings.fines-bank',compact('organization','fine_organization_columns','fine_organization_inputs','fine_organization_options','fineJson'));
    }
    
    //=======================================================================================
    
    public function operations($organization_id) {
        
        $organization = Organization::findOrFail($organization_id);

        $ticket_columns = Ticket::columnNames();
        $columnTickets = Ticket::columnInputs($organization);
        $optionTickets = Ticket::columnOptions($organization);
        $subtextOptionTickets = Ticket::columnSubtextOptions($organization);
        $ticket_attachment = AttachmentLabel::find(AttachmentLabel::TICKET_LABEL);
        $ticket_subtext_columns = Ticket::columnSubtextOptions( $organization );

        $support_columns = Support::columnNames($organization);
        $columnInputSupports = Support::columnInputs();
        $columnFoodSupports = Support::columnOptions($organization, OperationType::FOOD_SUPPORT);
        $columnWaterSupports = Support::columnOptions($organization, OperationType::WATER_SUPPORT);
        $subtextOptionSupports = Support::columnSubtextOptions($organization);
        $support_attachment = AttachmentLabel::find(AttachmentLabel::SUPPORT_LABEL);
        $support_subtext_columns = Support::columnSubtextOptions( $organization );
        
        $fine_columns = Fine::columnNames($organization);
        $fine_organization_inputs = FineOrganization::columnInputs();
        $fine_organization_options = FineOrganization::columnOptions($organization);

        return view('admin.organizations.settings.operations',compact(
            'organization',
            
            'ticket_columns',
            'columnTickets',
            'optionTickets',
            'subtextOptionTickets',
            'ticket_attachment',
            'ticket_subtext_columns',

            'support_columns',
            'columnInputSupports',
            'columnFoodSupports',
            'columnWaterSupports',
            'subtextOptionSupports',
            'support_attachment',
            'support_subtext_columns',

            'fine_columns',
            'fine_organization_inputs',
            'fine_organization_options',

        ));
    }
    
    //=======================================================================================
    
    public function categories($organization_id) {
        
        $organization = Organization::findOrFail($organization_id);
        $categories = Category::all();

        return view('admin.organizations.settings.categories',compact('organization','categories'));
    }
    
    //=======================================================================================
    
    public function sectorSetup($organization_id) {
        
        $organization = Organization::findOrFail($organization_id);
        $columnClassifications = Classification::columnInputs();
        $nationalities = Nationality::all();
        
        $sector_columns = Sector::columnNames();
        $columnSectors = Sector::columnInputs();
        $optionSectors = Sector::columnOptions($organization);
        $subtextOptionSectors = Sector::columnSubtextOptions($organization, 'guest_value_sar');
        $sector_attachment = AttachmentLabel::find(AttachmentLabel::SECTOR_SIGHT_LABEL);

        return view('admin.organizations.settings.sector-setup',compact(
            'organization',
            'columnClassifications',
            'nationalities',
            
            'sector_columns',
            'columnSectors',
            'optionSectors',
            'subtextOptionSectors',
            'sector_attachment',
        ));
    }
    
    //=======================================================================================
    
    public function countries($organization_id) {
        
        $organization = Organization::findOrFail($organization_id);
        $columnCountries = CountryOrganization::columnInputs();
        $optionCountries = CountryOrganization::columnOptions($organization);
        
        return view('admin.organizations.settings.countries',compact('organization','columnCountries','optionCountries'));
    }
    
    //=======================================================================================
    
    public function mealPreparation($organization_id) {
        
        $data = MealPreparationController::showData($organization_id);

        return view('admin.organizations.settings.meal-preparation',$data);
    }
    
    //=======================================================================================
    
    public function questionBank($organization_id) {
        
        $organization = Organization::findOrFail($organization_id);
        $questions_bank_columns = QuestionBankOrganization::columnNames();
        $questions_bank_inputs = QuestionBankOrganization::columnInputs();
        $questions_bank_options = QuestionBankOrganization::columnOptions();

        return view('admin.organizations.settings.question-banks',compact('organization','questions_bank_columns','questions_bank_inputs','questions_bank_options'));
    }
    
    //=======================================================================================
    
    public function contract($organization_id) {
        
        $organization = Organization::findOrFail($organization_id);
        $order_sector_dictionaries = Dictionary::where('type', 'order_sectors')->get();
        $user_dictionaries = Dictionary::where('type', 'users')->get();

        return view('admin.organizations.settings.contract',compact('organization','order_sector_dictionaries','user_dictionaries'));
    }
    
    //=======================================================================================
    
    public function providors($organization_id) {
        
        $organization = Organization::findOrFail($organization_id);
        return view('admin.organizations.settings.providors',compact('organization'));
    }
    
    //=======================================================================================
    
    public function assistQuestions($organization_id) {
    
        $organization = Organization::findOrFail($organization_id);
        $questions = $organization->assist_question->questions ?? null;
        return view('admin.organizations.settings.assist-questions', compact('organization','questions'));
    }
    
    
}
