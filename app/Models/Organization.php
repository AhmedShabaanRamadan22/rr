<?php

namespace App\Models;

use App\Models\OrganizationNew;
use App\Traits\AttachmentTrait;
use App\Traits\LocalizationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organization extends Base
{

	use SoftDeletes, AttachmentTrait, LocalizationTrait;
	protected $table = 'organizations';
	public $timestamps = true;

	const ALBAIT = 1;
	const ITHRAA = 2;
	const RAKAYA = 3;
	const THAKHIR = 5;
	const RAWAF = 6;
	const MCDC = 7;
	const ALJOUD = 8;

	protected $dates = ['deleted_at'];
	protected $fillable = array(
		'name_ar',
		'name_en',
		'domain',
		'cloudflare_custom_hostname_id',
		'sender_id',
		'about_us',
		'policies',
		'phone',
		'has_esnad',
		'close_registeration',
		'close_order',
		'primary_color',
		'contract',
        'email',
        'city_id',
        'district_id',
        'postal_code',
        'building_number',
        'sub_number',
        'release_date',
        'release_date_hj',
        'street_name',
		'registration_number',
        'registration_source',
		'license_id',
	);

	// protected $appends =  [
	// 	'logo',
	// 	'background_image',
	// 	'attachmentUrl',
	// 	'name',
	// 	'profile_file',
	// 	'has_classifications',
	// 	'has_employee_contract_template',
    //     'national_address',
	// 	// 'news'
	// ];

	protected $hidden = [
		'whatsapp_instance_id',
		'whatsapp_token',
	];


	public function country_organization()
	{
		return $this->hasMany(CountryOrganization::class);
	}

	public function countries()
	{
		return $this->belongsToMany(Country::class, CountryOrganization::class)->wherePivotNull('deleted_at');
	}

	public function organization_services()
	{
		return $this->hasMany(OrganizationService::class);
	}

	public function services()
	{
		return $this->belongsToMany(Service::class, OrganizationService::class)->wherePivotNull('deleted_at');
	}

	public function nationality_organizations()
	{
		return $this->hasMany(NationalityOrganization::class);
	}
    public function bravos()
	{
		return $this->hasMany(Bravo::class);
	}
	public function nationalities()
	{
		return $this->belongsToMany(Nationality::class, NationalityOrganization::class)->wherePivotNull('deleted_at');
	}

	// public function users(){
	// 	return $this->belongsToMany(User::class,OrganizationUser::class)->wherePivotNull('deleted_at');
	// }

	public function users()
	{
		return $this->hasMany(User::class);
	}

    public function contract_templates()
	{
		return $this->hasMany(ContractTemplate::class);
	}

    public function contract_template($type)
	{
		return $this->contract_templates->where('type', $type)->first();
	}

	public function organization_news()
	{
		return $this->hasMany(OrganizationNew::class, 'organization_id', 'id');
	}

	public function favourit_organizations()
	{
		return $this->belongsToMany(FavouritOrganization::class);
	}

	public function organization_categories()
	{
		return $this->hasMany(OrganizationCategory::class);
	}
	public function categories()
	{
		return $this->belongsToMany(Category::class, OrganizationCategory::class)->wherePivotNull('deleted_at');
	}

	public function orders()
	{
		return $this->hasManyThrough(Order::class, OrganizationService::class)->with('user');
	}

	public function accepted_orders() {
		return $this->orders->where('status_id',Status::ACCEPTED_ORDER);
	}
	public function organization_attachments_labels()
	{
		return $this->hasMany(OrganizationAttachmentLabel::class, );
	}

	public function reason_dangers()
	{
		return $this->hasMany(ReasonDanger::class);
	}

	public function messages()
	{
		return $this->hasMany(Message::class);
	}

	public function classifications()
	{
		return $this->hasMany(Classification::class);
	}

	public function sectors()
	{
		return $this->hasManyThrough(Sector::class,Classification::class)->orderByRaw("CAST(label AS UNSIGNED)");
	}

	public function meals(){
		$sector_ids = $this->sectors->pluck('id')->toArray();
		return Meal::whereIn('sector_id',$sector_ids)->get();
	}

	public function mealsJson(){
		$sector_ids = $this->sectors->pluck('id')->toArray();
		return Meal::with('sector','period')->whereIn('sector_id',$sector_ids)->get();
	}

	public function sender()
	{
		return $this->belongsTo(Sender::class);
	}

	public function chairman()
	{
		return $this->hasOne(User::class)->whereHas('roles', function($q){
			$q->whereIn('name', ['organization chairman'])->latest();
	});
	}

	public function chairmans()
	{
		return $this->hasMany(User::class)->whereHas('roles', function ($q) {
			$q->whereIn('name', ['organization chairman'])->latest();
		});
	}

	public function attachments()
	{
		return $this->morphMany(Attachment::class, 'attachmentable');
	}

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function registration_source_relation()
    {
        return $this->belongsTo(City::class, 'registration_source');
    }

	public function logo_attachment()
	{
		return $this->morphOne(Attachment::class, 'attachmentable')->where('attachment_label_id',AttachmentLabel::LOGO_LABEL)->latest('created_at');
	}

	public function assist_question()
	{
		return $this->hasOne(AssistQuestion::class);
	}

	public static function columnOptions($organization = null)
	{
		// ?? commented to optimize edit organization page
		$cities = City::all()->pluck('name', 'id')->toArray();
		
		return array(
			'menu' => FoodWeight::with('food:id,name,food_type_id', 'food.food_type:id,name')->where(['organization_id' => $organization->id])->get()->pluck('food_name', 'id')->toArray(),
			'food_weight' => FoodWeight::where('organization_id', $organization->id)
				->orderBy('food_id')->get()->map(function ($items, $key) {
					$items->option_group_label = $items->food->name;
					$items->name = $items->food_name;
					return $items;
				})->values()->toArray(),

			// ?? commented to optimize edit organization page
			'city_id' => $cities,
			'registration_source' => $cities,
			'organization_chairman' => User::where(function($q) use ($organization) {
					$q->whereNull('organization_id')->orWhere('organization_id', $organization->id);
				})->whereHas('roles', function ($q) use ($organization) {
					$q->whereIn('name', ['organization chairman']);
				})->pluck('name', 'id')->toArray(),
			'district_id' => District::all()->pluck('name', 'id')->toArray(),
			'sender_id' => Sender::wheredoesntHave('organization')->orWhereHas('organization',function($q) use ($organization){
				$q->where('id',$organization->id);
			})->get()->pluck('name', 'id')->toArray(),
		);
	}

	public function getLogoAttribute()
	{
		return $this->logo_attachment->url ?? null;
	}

	public function background_image_attachment()
	{
		return $this->morphOne(Attachment::class, 'attachmentable')->where('attachment_label_id',AttachmentLabel::BACKGROUND_LABEL)->latest('created_at');
	}

	public function getBackgroundImageAttribute()
	{
		return $this->background_image_attachment->url ?? null;
	}

	public function profile_attachment()
	{
		return $this->morphOne(Attachment::class, 'attachmentable')->where('attachment_label_id',AttachmentLabel::PROFILE_FILE_LABEL)->latest('created_at');
	}

	public function getProfileFileAttribute()
	{
		return $this->profile_attachment->url ?? null;
	}

	public function getAttachmentUrlAttribute(){
		return $this->attachment_url_response_shape($this->attachments, $this);
	}

	public function getNameAttribute(){
		return $this->localizeName();
	}

	public function getHasClassificationsAttribute(){
		return $this->classifications->isNotEmpty();
	}

	public function getHasSectorsAttribute(){
		return $this->sectors->isNotEmpty();
	}

	public function getNationalAddressAttribute(){
		$data = [$this->street_name ?? '',$this->district->name ?? '', $this->city->name ?? '', $this->building_number ?? '', $this->postal_code ?? '',$this->sub_number ?? ''];
		$data = array_filter($data);
		return implode(', ',$data);
	}

	public function question_bank_organizations(){
		return $this->hasMany(QuestionBankOrganization::class);
	}

	public function fine_organizations(){
		return $this->hasMany(FineOrganization::class);
	}

	public function getHasEmployeeContractTemplateAttribute(){
		return $this->contract_template('employee_' . $this->id) ? true : false;
	}

	// public function getNewsAttribute(){
	// 	return $this->organization_news()->get()?? [];
	// }

	public function food_weights()
	{
		return $this->hasMany(FoodWeight::class);
	}

	public function organization_stages()
	{
		return $this->hasMany(OrganizationStage::class);
	}
	
	public function getOperationalManagerNameAttribute(){
		if(in_array($this->id ,[$this::THAKHIR,$this::RAWAF,$this::MCDC])) return 'كنان جمل الليل';
		if(in_array($this->id ,[$this::ITHRAA,$this::ALJOUD])) return 'احمد منشاوي	';
		return 'لا يوجد بيانات';

	}


}