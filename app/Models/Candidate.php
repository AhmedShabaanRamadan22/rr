<?php

namespace App\Models;

use App\Traits\UuidableTrait;
use App\Traits\AttachmentTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Candidate extends Base
{
    use SoftDeletes, AttachmentTrait, UuidableTrait;

    protected $table = 'candidates';
    public $timestamps = true;

    protected $dates = ['created_at', 'deleted_at'];
    protected $fillable = array('name', 'email', 'phone', 'phone_code', 'qualification', 'job_category', 'marital_status', 'department_id', 'status_id', 'self_description', 'gender', 'resident_status', 'salary_expectation', 'availability_to_start', 'years_of_experience', 'national_id', 'nationality', 'birthdate', 'birthdate_hj', 'address', 'previously_work_at_rakaya' ,'has_relative', 'scrub_size', 'is_cloned');
    protected static $yearsOfExperience = array(
        '0' => ['en' => 'No Experience', 'ar' => 'لاتوجد خبرة'],
        '1' => ['en' => '1 Year', 'ar' => 'سنة'],
        '2' => ['en' => '2 Years', 'ar' => 'سنتين'],
        '3' => ['en' => '3 Years', 'ar' => '٣ سنوات'],
        '4' => ['en' => '4 Years', 'ar' => '٤ سنوات'],
        '5' => ['en' => '5 Years', 'ar' => '٥ سنوات'],
        '+6' => ['en' => '6 Years and More', 'ar' => '٦ سنوات وأكثر'],
        '+10' => ['en' => '10 Years and More', 'ar' => '١٠ سنوات وأكثر'],
    );

    protected static $residentStatus = array(
        'citizen' => ['en' => 'Citizen', 'ar' => 'مواطن'],
        'resident' => ['en' => 'Resident', 'ar' => 'مقيم'],
        'visitor' => ['en' => 'Visitor', 'ar' => 'زائر'],
        'other' => ['en' => 'Other', 'ar' => 'آخر'],

    );

    protected static $gender = array(
        'male' => ['en' => 'Male', 'ar' => 'ذكر'],
        'female' => ['en' => 'Female', 'ar' => 'أنثى'],
    );

    // protected $appends = ['department_name', 'attachmentUrl', 'resident_status_name', 'availability_to_start_name', 'candidate_cv_attachment_url', 'candidate_portfolio_attachment_url', 'candidate_profile_personal_attachment_url', 'code', 'job_category_name', 'marital_status_name', 'gender_name', 'qualification_name', 'candidate_status_name', 'years_of_experience_name', 'nationality_name', 'scrub_size_name','candidate_iban_attachment_url','candidate_national_id_attachment_url','uuid_url', 'bank_name', 'iban_number', 'account_name', 'owner_national_id'];

    protected $attributes = [
        'status_id' => 21,
    ];

    public function attachment()
    {
        return $this->morphOne(Attachment::class, 'attachmentable');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }

    public function iban()
    {
        return $this->morphOne(Iban::class, 'ibanable')->latest();
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'nationality');
    }

    public function getBankInformationAttribute()
    {
        return $this->iban()->latest()->first() ?? '';
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function attachment_candidate_cv()
    {
        return $this->morphOne(Attachment::class, 'attachmentable')->where('attachment_label_id', AttachmentLabel::CANDIDATE_CV_LABEL)->latest('created_at');
    }

    public function attachment_candidate_portfolio()
    {
        return $this->morphOne(Attachment::class, 'attachmentable')->where('attachment_label_id', AttachmentLabel::CANDIDATE_PORTFOLIO_LABEL)->latest('created_at');
    }

    public function attachment_candidate_profile_personal()
    {
        return $this->morphOne(Attachment::class, 'attachmentable')->where('attachment_label_id', AttachmentLabel::CANDIDATE_PROFILE_PERSONAL_LABEL)->latest('created_at');
    }

    public function attachment_candidate_national_id()
    {
        return $this->morphOne(Attachment::class, 'attachmentable')->where('attachment_label_id', AttachmentLabel::CANDIDATE_NATIONAL_ID)->latest('created_at');
    }

    public function attachment_candidate_iban()
    {
        return $this->morphOne(Attachment::class, 'attachmentable')->where('attachment_label_id', AttachmentLabel::CANDIDATE_IBAN)->latest('created_at');
    }


    public function attachment_candidate_education_certificate()
    {
        return $this->morphOne(Attachment::class, 'attachmentable')->where('attachment_label_id', AttachmentLabel::CANDIDATE_EDUCATION_CERTIFICATE)->latest('created_at');
    }

    public function attachment_candidate_course_certificate()
    {
        return $this->morphOne(Attachment::class, 'attachmentable')->where('attachment_label_id', AttachmentLabel::CANDIDATE_COURSE_CERTIFICATE)->latest('created_at');
    }

    public function attachment_candidate_experience_certificate()
    {
        return $this->morphOne(Attachment::class, 'attachmentable')->where('attachment_label_id', AttachmentLabel::CANDIDATE_EXPERIENCE_CERTIFICATE)->latest('created_at');
    }

    public function attachment_candidate_cv_en()
    {
        return $this->morphOne(Attachment::class, 'attachmentable')->where('attachment_label_id', AttachmentLabel::CANDIDATE_CV_EN)->latest('created_at');
    }

    public function attachment_candidate_passport()
    {
        return $this->morphOne(Attachment::class, 'attachmentable')->where('attachment_label_id', AttachmentLabel::CANDIDATE_PASSPORT)->latest('created_at');
    }

    public function attachment_candidate_driving_license()
    {
        return $this->morphOne(Attachment::class, 'attachmentable')->where('attachment_label_id', AttachmentLabel::CANDIDATE_DRIVING_LICENSE)->latest('created_at');
    }

    public function attachment_candidate_national_address()
    {
        return $this->morphOne(Attachment::class, 'attachmentable')->where('attachment_label_id', AttachmentLabel::CANDIDATE_NATIONAL_ADDRESS)->latest('created_at');
    }


    public function status()
    {
        return $this->belongsTo(Status::class)->where('type', 'candidates');
    }

    public static function columnNames( $all_columns = false )
    {
        $columns_array = array(
            'id'                         => 'id',
            'code'                       => 'code',
            'name'                       => 'name',
            'gender'                     => 'gender',
            'email'                      => 'email',
            'phone'                      => 'phone',
            // 'department_name'            => 'department',
            // 'availability_to_start_name' => 'availability_to_start',
            'status'                     => 'candidate-status',
            'department_name'            => 'department',
            'years_of_experience_name'   => 'years_of_experience',
            'resident_status_name'       => 'resident_status',
            'job_category_name'          => 'job_category',
            // 'uuid_url'                   => 'uuid-url',
            'created_at'                 => 'create-time',
            'updated_at'                 => 'update-time',
            'action'                     => 'action'
        );
        if ( $all_columns ) {
            $columns_array = array_merge( $columns_array, array(
                'qualification_name'                   => 'qualification',
                'self_description'                     => 'self_description',
                'resident_status_name'                 => 'resident_status',
                'marital_status_name'                  => 'marital_status',
                'salary_expectation'                   => 'salary_expectation',
                'iban_number'                            => 'iban_number',
                'bank_name'                       => 'bank_name',
                'account_name'                    => 'account_name',
                'owner_national_id'               => 'owner_national_id',
                'candidate_profile_personal_attachment_url'          => 'candidate_profile_personal',
                'candidate_cv_attachment_url'          => 'candidate_cv',
                'candidate_portfolio_attachment_url'   => 'candidate_portfolio',
                'candidate_iban_attachment_url'        => 'candidate_iban',
                'candidate_national_id_attachment_url' => 'candidate_national_id',
                'candidate_education_certificate_attachment_url' => 'candidate_education_certificate',

                'candidate_course_certificate_attachment_url' => 'candidate_course_certificate',
                'candidate_experience_certificate_attachment_url' => 'candidate_experience_certificate',
                'candidate_cv_en_attachment_url' => 'candidate_cv_en',
                'candidate_passport_attachment_url' => 'candidate_passport',
                'candidate_driving_license_attachment_url' => 'candidate_driving_license',
                'candidate_national_address_attachment_url' => 'candidate_national_address',

                'national_id'                          => 'national-id',
                'nationality_name'                     => 'nationality-name',
                'birthdate'                            => 'birthday',
                'birthdate_hj'                         => 'birthday-hj',
                'address'                              => 'address',
                'previously_work_at_rakaya'            => 'previously-work-at-rakaya',
                'has_relative'                         => 'has-relative',
                'scrub_size_name'                      => 'scrub-size-name'
            ) );
        }
        return $columns_array;
    }

    public static function columnInputs()
    {
        return array(
            //            'candidate_status_name' => 'text',
            //            'candidate_profile_personal_attachment_url' => 'text',
            'name' => 'text',
            'status' => 'text',
            'code' => 'text',
            'email' => 'text',
            'phone' => 'text',
            'gender_name' => 'text',
            'qualification_name' => 'text',
            'department_name' => 'text',
            'national_id'                => 'text',
            'nationality_name'           => 'text',
            'birthdate'                  => 'text',
            'birthdate_hj'               => 'text',
            'address'                    => 'text',
            'previously_work_at_rakaya'  => 'text',
            'has_relative'               => 'text',
            'scrub_size_name'            => 'text',
            'years_of_experience_name' => 'text',
            //            'self_description' => 'text',
            'resident_status_name' => 'text',
            'iban_number'                  => 'text',
            'bank_name'             => 'text',
            'account_name'          => 'text',
            'owner_national_id'     => 'text',
            'job_category_name' => 'text',
            'marital_status_name' => 'text',
            'salary_expectation' => 'text',
            'availability_to_start_name' => 'text',
            'candidate_cv_attachment_url' => 'text',
            'candidate_portfolio_attachment_url' => 'text',
            'candidate_national_id_attachment_url' => 'text',
            'candidate_iban_attachment_url' => 'text',

            'candidate_education_certificate_attachment_url' => 'text',
            'candidate_course_certificate_attachment_url' => 'text',
            'candidate_experience_certificate_attachment_url' => 'text',
            'candidate_cv_en_attachment_url' => 'text',
            'candidate_passport_attachment_url' => 'text',
            'candidate_driving_license_attachment_url' => 'text',
            'candidate_national_address_attachment_url' => 'text',
        );
    }

    public static function columnOptions()
    {

        return array(
            'status' => Status::candidate_statuses()->pluck('name_ar', 'id')->toArray(),
        );
    }


    public function getResidentStatusNameAttribute()
    {
        $residentStatus = self::$residentStatus;
        $locale = app()->getLocale();
        
        return isset($residentStatus[$this->resident_status][$locale]) ? $residentStatus[$this->resident_status][$locale] : $this->resident_status;
    }
    
    public function getGenderNameAttribute()
    {
        $genderName =  self::$gender;
        $locale = app()->getLocale();
        
        return isset($genderName[$this->gender][$locale]) ? $genderName[$this->gender][$locale] : $this->gender;
    }
    public function getScrubSizeNameAttribute()
    {
        $scrubSize = [
            's' => 'small',
            'm' => 'medium',
            'l' => 'large',
            'xl' => 'x-large',
            '2xl' => '2x-large',
            '3xl' => '3x-large',
            '4xl' => '4x-large',
            '5xl' => '5x-large',
        ];

        return $scrubSize[$this->scrub_size]?? trans('translation.no-data');
    }

    public function getQualificationNameAttribute()
    {
        $qualificationName = [
            'high_school' => ['en' => 'High School', 'ar' => 'المدرسة الثانوية'],
            'diploma' => ['en' => 'diploma', 'ar' => 'دبلوم'],
            'bachelor' => ['en' => 'Bachelor', 'ar' => 'بكالوريوس'],
            'master' => ['en' => 'Master', 'ar' => 'ماجستير'],
            'phd' => ['en' => 'PhD', 'ar' => 'دكتوراه'],

        ];

        $locale = app()->getLocale();

        return isset($qualificationName[$this->qualification][$locale]) ? $qualificationName[$this->qualification][$locale] : $this->qualification;
    }


    public function getJobCategoryNameAttribute()
    {
        $jobCategoryName = [
            'full_time' => ['en' => 'Full Time', 'ar' => 'دوام كامل'],
            'part_time' => ['en' => 'Part Time', 'ar' => 'دوام جزئي'],
            'remotely' => ['en' => 'Remotely', 'ar' => 'عن بعد'],
            'hybrid' => ['en' => 'Hybrid', 'ar' => 'عن بعد / دوام جزئي'],
            'training' => ['en' => 'Cooperative / Summer Training', 'ar' => 'تدريب تعاوني / صيفي'],
            'seasonal' => ['en' => 'Seasonal', 'ar' => 'دوام موسمي'],
        ];

        $locale = app()->getLocale();

        return isset($jobCategoryName[$this->job_category][$locale]) ? $jobCategoryName[$this->job_category][$locale] : $this->job_category;
    }


    public function getYearsOfExperienceNameAttribute()
    {
        $YearsOfExperienceName = self::$yearsOfExperience;

        $locale = app()->getLocale();

        return isset($YearsOfExperienceName[$this->years_of_experience][$locale]) ? $YearsOfExperienceName[$this->years_of_experience][$locale] : $this->years_of_experience;
    }



    public function getMaritalStatusNameAttribute()
    {
        $maritalStatusName = [
            'single' => ['en' => 'Single', 'ar' => 'أعزب'],
            'married' => ['en' => 'Married', 'ar' => 'متزوج'],
            'divorced' => ['en' => 'Divorced', 'ar' => 'مطلق'],
            'widowed' => ['en' => 'Widowed', 'ar' => 'أرمل'],
            'other' => ['en' => 'Other', 'ar' => 'أخرى'],
        ];

        $locale = app()->getLocale();

        return isset($maritalStatusName[$this->marital_status][$locale]) ? $maritalStatusName[$this->marital_status][$locale] : $this->marital_status;
    }


    public function getAvailabilityToStartNameAttribute()
    {
        $availabilityToStart = [
            'now' => ['en' => 'Now', 'ar' => 'الآن'],
            'two_to_four_weeks' => ['en' => 'Two to four weeks', 'ar' => 'من أسبوعين إلى أربعة أسابيع'],
            'four_to_eight_weeks' => ['en' => 'Four to eight weeks', 'ar' => 'من أربعة أسابيع إلى ثمانية أسابيع'],
            'more_than_eight_weeks' => ['en' => 'More than eight weeks', 'ar' => 'أكثر من ثمانية أسابيع'],
        ];

        $locale = app()->getLocale();

        return isset($availabilityToStart[$this->availability_to_start][$locale]) ? $availabilityToStart[$this->availability_to_start][$locale] : $this->availability_to_start;
    }

    public function attachments_arranged()
    {
        return $this->morphMany(Attachment::class, 'attachmentable')
            ->join('attachment_labels', 'attachments.attachment_label_id', '=', 'attachment_labels.id')
            ->orderBy('attachment_labels.arrangement');
    }


    public function getDepartmentNameAttribute()
    {
        return app()->getLocale() == 'en' ? $this->department->name_en : $this->department->name_ar;
    }

    public function getAttachmentUrlAttribute()
    {
        return $this->attachment_url_response_shape($this->attachments, $this);
    }

    public function getCandidateCvAttachmentUrlAttribute()
    {
        return $this->attachment_candidate_cv->url ?? '-';
    }

    public function getCandidatePortfolioAttachmentUrlAttribute()
    {
        return $this->attachment_candidate_portfolio->url ?? '-';
    }

    public function getCandidateProfilePersonalAttachmentUrlAttribute()
    {
        return $this->attachment_candidate_profile_personal->url ?? '-';
    }

    public function getCandidateNationalIdAttachmentUrlAttribute()
    {
        return $this->attachment_candidate_national_id->url ?? '-';
    }

    public function getCandidateIbanAttachmentUrlAttribute()
    {
        return $this->attachment_candidate_iban->url ?? '-';
    }


    public function getCandidateEducationCertificateAttachmentUrlAttribute()
    {
        return $this->attachment_candidate_education_certificate->url ?? '-';
    }

    public function getCandidateCourseCertificateAttachmentUrlAttribute()
    {
        return $this->attachment_candidate_course_certificate->url ?? '-';
    }

    public function getCandidateExperienceCertificateAttachmentUrlAttribute()
    {
        return $this->attachment_candidate_experience_certificate->url ?? '-';
    }

    public function getCandidateCvEnAttachmentUrlAttribute()
    {
        return $this->attachment_candidate_cv_en->url ?? '-';
    }

    public function getCandidatePassportAttachmentUrlAttribute()
    {
        return $this->attachment_candidate_passport->url ?? '-';
    }

    public function getCandidateDrivingLicenseAttachmentUrlAttribute()
    {
        return $this->attachment_candidate_driving_license->url ?? '-';
    }

    public function getCandidateNationalAddressAttachmentUrlAttribute()
    {
        return $this->attachment_candidate_national_address->url ?? '-';
    }



    public function getUuidUrlAttribute()
    {
        if(isset($this->uuid)){
            return 'https://rakaya.sa/jobs/complete-application?cnd_token=' . $this->uuid;
        }
        return trans('translation.not-found');
    }

    public function getCandidateStatusNameAttribute()
    {
        return $this->status ?? '-';
    }

    public function getBankNameAttribute()
    {
        return $this->iban?->bank_name ?? trans('translation.no-data');
    }

    public function getIbanNumberAttribute()
    {
        return $this->iban?->iban ?? trans('translation.no-data');
    }

    public function getAccountNameAttribute()
    {
        return $this->iban?->account_name ?? trans('translation.no-data');
    }

    public function getOwnerNationalIdAttribute()
    {
        return $this->iban?->owner_national_id ?? trans('translation.no-data');
    }

    public function getCodeAttribute()
    {
        $code = 'CDT-' . $this->department->slug . str_pad($this->id, 5, '0', STR_PAD_LEFT);
        return $code;
    }

    public static function filterColumns()
    {
        $locale = app()->getLocale();
        return array(
            'department_name' => Department::get()->pluck('name', 'id')->toArray(),
            'status_id' => Status::where('type', 'candidates')->get()->pluck('name', 'id')->toArray(),
            'years_of_experience' => collect(self::$yearsOfExperience)->map(function($item) use ($locale) {
                return $item[$locale];
            })->toArray(),

            'resident_status' => collect(self::$residentStatus)->map(function($item) use ($locale) {
                return $item[$locale];
            })->toArray(),

            'gender' => collect(self::$gender)->map(function($item) use ($locale) {
                return $item[$locale];
            })->toArray(),

            // 'sector_id' => Sector::get()->pluck('label', 'id')->toArray(),
            // 'fine_id' => FineBank::get()->pluck('name', 'id')->toArray(),
            // 'user_id' => User::get()->pluck('name', 'id')->toArray(),
        );
    }

    public function getNationalityNameAttribute()
    {
        $this->setHidden(['country']);
        return $this->country->name ?? trans('translation.no-data');
    }
}
