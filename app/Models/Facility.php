<?php

namespace App\Models;

use App\Models\Iban;
use App\Traits\AttachmentTrait;
use App\Traits\UuidableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Facility extends Base
{
    use SoftDeletes, AttachmentTrait, UuidableTrait;


    protected $table = 'facilities';
    public $timestamps = true;

    protected $dates = ['deleted_at'];
    protected $fillable = array(
        'registration_number', 'name', 'version_date', 'version_date_hj',
        'end_date', 'end_date_hj', 'registration_source', 'capacity',
        'license', 'license_expired', 'license_expired_hj', 'tax_certificate',
        'employee_number', 'chefs_number', 'kitchen_space', 'user_id',
        'building_number', 'street_name', 'district_id', 'city_id', 'postal_code', 'sub_number','uuid'
    );

    // protected $appends = ['attachmentUrl', 'all_facility_employees', 'is_authorized', 'city', 'registration_source_name', 'district', 'bank_information', 'national_address'];


    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function iban()
    {
        return $this->morphOne(Iban::class, 'ibanable');
    }

    public function facility_services()
    {
        return $this->hasMany(FacilityService::class);
    }

    public function facility_evaluations()
    {
        return $this->hasMany(FacilityEvaluation::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }

    public function attachments_arranged()
    {
        return $this->morphMany(Attachment::class, 'attachmentable')
                ->join('attachment_labels', 'attachments.attachment_label_id', '=', 'attachment_labels.id')
                ->orderBy('attachment_labels.arrangement');
    }

    public function facility_employees()
    {
        return $this->hasMany(FacilityEmployee::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id');//->first();
    }

    public function district()
    {
        return $this->hasOne(District::class, 'id', 'district_id');//->first();
    }

    public function registration_source()
    {
        return $this->hasOne(City::class, 'id', 'registration_source');//->first();
    }
    public function registration_src()
    {
        return $this->hasOne(City::class, 'id', 'registration_source');//->first();
    }

    public function getAttachmentUrlAttribute()
    {
        return $this->attachment_url_response_shape($this->attachments, $this);
    }

    public function getIsAuthorizedAttribute()
    {
        return $this->user_id === (auth()->user()->id ?? 0);
    }

    public function getAllFacilityEmployeesAttribute()
    {
        $employees = $this->facility_employees ?? [];
        $this->unsetRelations(['facility_employees']);
        return $employees;
    }

    public function getTotalGuestQuantityAttribute()
    {
        return $this->orders()
            ->with('order_sectors.sector')
            ->get()
            ->flatMap(function ($order) {
                return $order->order_sectors;
            })
            ->sum(function ($order_sector) {
                return $order_sector->sector->guest_quantity??0;
            });
    }

    public function getRemainCapacityAttribute(){
        return (($this->capacity??0) - ($this->total_guest_quantity??0)) .'/'. ($this->capacity??0); 
    }

    public static function columnNames()
    {
        $evaluations = (collect(FacilityEvaluation::SEASONS)
            ->mapWithKeys(fn($v, $k) => ["$k-h" => "evaluation-$v"])
            ->toArray());
        return [
            'id' => 'id',
            'facility-name' => 'facility-name',
            'facility-owner-name' => 'facility-owner-name',
            'user-email' => 'user_email',
            'user-birthday' => 'user_birthday',
            'user-nationality' => 'user_nationality',
            'user-national-id' => 'user_national_id',
            'user-national-id-issue-city' => 'user_national_id_issue_city',
            'user-phone-num' => 'user-phone-num',
            'registration-num' => 'registration-num',
            'version_date' => 'version_date',
            'end-date' => 'end_date',
            'registration-source' => 'facility-registration_source',
            'license' => 'license',
            'national-address' => 'facility-national_address',
            'service-name' => 'service-name',
            'organization-name' => 'organization-name',
            
        ] 
        + $evaluations
        + [
        'more-details' => 'more-details'

        ];
    }
    public static function columnOptions()
    {
        $cities = City::all()->pluck('name', 'id')->toArray();
        return array(
            'registration_source' => $cities,
            'district_id' => District::all()->pluck('name', 'id')->toArray(),
            'user_id' => User::all()->pluck('name', 'id')->toArray(),
            'city_id' => $cities,
            'bank' => Bank::all()->pluck('name', 'id')->toArray(),
        );
    }
    public static function columnInputs()
    {
        return array(
            'name' => 'text',
            'user_id' => 'select',
            'registration_number' => 'text',
            'version_date' => 'date',
            'end_date' => 'date',
            'registration_source' => 'select',
            'license' => 'text',
            'license_expired' => 'date',
            'capacity' => 'number',
            'tax_certificate' => 'number',
            'account_name' => 'text',
            'bank' => 'select',
            'iban' => 'text',
            'city_id' => 'select',
            'district_id' => 'select',
            'street_name' => 'text',
            'building_number' => 'number',
            'postal_code' => 'number',
            'sub_number' => 'number',
            'employee_number' => 'number',
            'chefs_number' => 'number',
            'kitchen_space' => 'number',
            // 'nationality_address' => 'file',
            // 'work_license' => 'file',
            // 'registration' => 'file',
            // 'national_id' => 'file',
        );
    }

    public static function hijriDateColumns()
    {
        return array(
            'version_date' => 'version_date',
            'end_date' => 'end_date',
            'license_expired' => 'license_expired',
        );
    }

    public function getCityNameAttribute()
    {
        return $this->city->name ?? '';
    }

    public function getRegistrationSourceNameAttribute()
    {
        return $this->registration_source()->first()->name ?? '';
    }

    public function getDistrictNameAttribute()
    {
        return $this->district->name ?? '';
    }

    public function getBankInformationAttribute()
    {
        return $this->iban()->latest()->first() ?? '';
    }
    public function getNationalAddressAttribute()
    {
        $data = [$this->city->name ?? '', $this->district->name ?? '', $this->street_name ?? '',  $this->building_number ?? '', $this->postal_code ?? '', $this->sub_number ?? ''];
        $data = array_filter($data);
        return implode(' - ', $data);
    }

    public function getRegistrationNumberAndLicenseAttribute()
    {
        return ($this->registration_number ?? '') . ' - ' . ($this->license ?? '');
    }

    public function hasAssignees()
    {
        return $this->orders()->whereHas('assignees')->exists();
    }
    
    public function get_orders_assignees()
    {
        return $this->orders->flatMap->assignees->unique();

    }
}
