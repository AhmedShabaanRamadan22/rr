<?php

namespace App\Models;

use App\Traits\LocalizationTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class AttachmentLabel extends Base
{
    use SoftDeletes, LocalizationTrait;
    protected $table = 'attachment_labels';
    public $timestamps = true;
    protected $dates = ['created_at', 'deleted_at'];
    protected $fillable = ['label', 'placeholder_ar', 'placeholder_en','type', 'is_required', 'extensions', 'arrangement'];

    protected $casts = [
        'extensions' => 'array'
    ];
	// protected $appends =  ['placeholder'];

	//user
	const NATIONAL_ID_LABEL = 1;
    const PROFILE_PHOTO_LABEL = 2;
    const USER_IBAN_LABEL = 3;

	//organization
	const PROFILE_FILE_LABEL = 4;
	const LOGO_LABEL = 5;
	const BACKGROUND_LABEL = 6;

	//facility
	const OWNER_ID_LABEL = 7;//'هوية مالك المنشأة';
	const MANAGER_ID_LABEL = 8;//'هوية مدير المنشأة حسب السجل التجاري';
	const COMMERCIAL_REGISTERATION_LABEL = 9;//'شهادة السجل التجاري';
	const CHAMBER_MEMBERSHIP_CERTIFICATE_LABEL = 10;//'شهادة انتساب الغرفة التجارية';
	const CIVIL_DEFENSE_LICENSE_LABEL = 11;//'ترخيص الدفاع المدني';
	const COMMERCIAL_ACTIVITY_LICENSE_LABEL = 12;//'رخصة النشاط التجاري (بلدي)';
	const ZAKAT_INCOME_REGISTRATION_CERTIFICATE_LABEL = 13;//'شهادة تسجيل هيئة الزكاة والدخل';
	const VAT_CERTIFICATE_LABEL = 14;//'شهادة تسجيل في ضريبة القيمة المضافة';
	const SOCIAL_INSURANCE_CERTIFICATE_LABEL = 15;//'شهادة التأمينات الاجتماعية';
	const SAUDIZATION_CERTIFICATE_LABEL = 16;//'شهادة سعودة';
	const NATIONAL_ADDRESS_LABEL = 17;//'العنوان الوطني';
	const IBAN_NUMBER_LABEL = 18;//'رقم آيبان مختوم من البنك';
	const ARTICLES_OF_ASSOCIATION_LABEL = 19;//'عقد التأسيس (فقط للشركات)';
	const BUSINESS_FILE_LABEL = 20;//'ملف الأعمال';
	const CERTIFICATE_OF_ACHIEVEMENT = 41;//'شهادة إنجاز';
	const THANKFUL_LETTER = 42;//'خطاب شكر';
	const MISSION_ENDORSEMENT = 43;//'خطاب تأييد أو اعتماد من البعثة';
	const PREVIOUS_MENUES = 44;//'قوائم طعام سابقة';

	//facility employee
	const EMPLOYEE_NATIONAL_ID_LABEL = 21;
    const PERSONAL_PHOTO_LABEL = 22;
    const WORK_CARD_LABEL = 23;
    const HEALTH_CARD_LABEL = 24;
    const EMPLOYEE_CV = 40;

	//ticket
	const TICKET_LABEL = 25;

	//support
	const SUPPORT_LABEL = 26;

	//assist
	const ASSIST_SIGNATURE_LABEL = 27;
    const ASSIST_MEDIA_LABEL = 28;

	// //order
	// const ORDER_LABEL = 29;

	//answer
	const ANSWER_LABEL = 29;

	//fine
	const FINE_LABEL = 30;

	//candidate
	const CANDIDATE_CV_LABEL = 31;

    const CANDIDATE_PORTFOLIO_LABEL = 32;

    const CANDIDATE_PROFILE_PERSONAL_LABEL = 33;
    const CANDIDATE_NATIONAL_ID = 46;
    const CANDIDATE_IBAN = 45;

    const CANDIDATE_EDUCATION_CERTIFICATE = 48;
    const CANDIDATE_COURSE_CERTIFICATE = 49;
    const CANDIDATE_EXPERIENCE_CERTIFICATE = 50;
    const CANDIDATE_CV_EN = 51;
    const CANDIDATE_PASSPORT = 52;
    const CANDIDATE_DRIVING_LICENSE = 53;
    const CANDIDATE_NATIONAL_ADDRESS = 54;


	//contract
    const CONTRACT_LABEL = 38;
    const SIGNED_CONTRACT_LABEL = 39;

	//sector
	const SECTOR_SIGHT_LABEL = 47;

	//mobile info
	const ANDROID_APP_BUNDLE = 56;
	const IOS_APP_BUNDLE = 57;


    public function organization_attachments()
    {
        return $this->hasMany(OrganizationAttachmentLabel::class,);
    }

    public function attachments(){
        return $this->hasMany(Attachment::class);
    }


	public static function columnNames()
	{
		return array(
			'id' => 'id',
			'placeholder_ar' => 'placeholders AR',
			'placeholder_en' => 'placeholders EN',
			'type' => 'attachment type',
			'arrangement' => 'arrangement',
			'is_required' => 'required',
			'extensions' => 'extensions',
			'label' => 'attachment_label',
			'action' => 'action',
		);
	}

	public static function columnInputs()
	{
		return array(
			'label' => 'text',
			'arrangement' => 'number',
			'placeholder_ar' => 'text',
			'placeholder_en' => 'text',
			'type' => 'select',
			'is_required' => 'switch',
			'extensions' => 'multiple-select',
			// 'organization_id' => 'hidden',
		);
	}


	public static function columnOptions()
	{
		return array(
			'extensions' => [
				'pdf' => 'PDF',
				'png' => 'PNG',
				'jpg' => 'JPG',
				'jpeg' => 'JPEG',
				'csv' => 'CSV',
			],
            'type' => array_reduce(array_map("current",DB::select('SHOW TABLES;')),function($acc,$table){ return array_merge($acc,[$table => $table]);}, []),
		);
	}

	public static function filterColumns(){
		return array(
			'type' => AttachmentLabel::get()->pluck('type', 'id')->unique()->toArray(),
		);
	}

	public function getPlaceholderAttribute(){
		return $this->localize($this->placeholder_en, $this->placeholder_ar);
	}

	public function getRequiredTextAttribute(){
		return $this->is_required == 1 ? trans('translation.required') : trans('translation.optional');
	}

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

}
