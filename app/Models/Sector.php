<?php


namespace App\Models;

use App\Traits\AttachmentTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sector extends Base
{
    protected $table = 'sectors';
    public $timestamps = true;

    use SoftDeletes, AttachmentTrait;

    protected $dates = ['created_at', 'deleted_at'];
    protected $fillable = [
        'label',
        'sight',
        'guest_quantity',
        'classification_id',
        'nationality_organization_id',
        'longitude',
        'latitude',
        'manager_id',
        'boss_id',
        'supervisor_id',
        'location',
        'note',
        'arafah_longitude',
        'arafah_latitude',
        'kitchen_quantity',
        'license_number',
        'camp_number',
        'block_number',
        'track_package_number',
        'arafah_location',
    ];
    // protected $appends = ['organization_name', 'cost_all'];//, 'boss_name', 'supervisor_name'];

    const SECTOR_MANAGER = 'sector manager';
    const MONITOR = 'monitor';

    public function order_sectors()
    {
        return $this->hasMany(OrderSector::class);
    }

    public function attachment()
    {
        return $this->morphOne(Attachment::class, 'attachmentable');
    }
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }

    public function active_order_sectors() //it means that these are the orders that will be used to submit forms and generates contracts
    { //نقدر نوصلها بطريقة تانية من ال مون-اورد-سك لأن ماحيتخزن فيه الا الاوردرز الرئيسية
        return $this->hasMany(OrderSector::class)->whereNull('parent_id');
    }
    public function order_sector_service($service)
    {
        return $this->order_sectors->where('order.organization_service.id', $service);
    }
    public function order_sector_organization_service($service)
    {
        return $this->order_sectors->where('order.organization_service.service.id', $service);
    }
    public function active_order_sector_service($service) //it means that these are the orders that will be used to submit forms and generates contracts
    {
        return $this->order_sector_service($service)->whereNull('parent_id');
    }
    public function active_order_sector_organization_service($service) //it means that these are the orders that will be used to submit forms and generates contracts
    {
        return $this->order_sector_organization_service($service)->whereNull('parent_id');
    }
    public function monitor_order_sectors()
    {
        return $this->hasManyThrough(MonitorOrderSector::class, OrderSector::class);
    }
    public function monitor() {}
    public function supports()
    {
        return $this->hasMany(Support::class);
    }
    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }
    public function getCostAllAttribute()
    {
        return $this->guest_quantity * ($this->classification->guest_value??0);
    }
    public function classification()
    {
        return $this->belongsTo(Classification::class);
    }
    public function organization()
    {
        // dd(is_null($this->classification));
        // if(is_null($this->classification)){
        //     // dd($this);
        //     return null;
        // }
        return $this->classification->organization();
    }
    public function nationality_organization()
    {
        return $this->belongsTo(NationalityOrganization::class);
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    public function meals()
    {
        return $this->hasMany(Meal::class);
    }
    public function monitors()
    {
        return $this->belongsToMany(Monitor::class, 'monitor_order_sectors');
    }
    // public function monitor()
    // {
    //     return $this->belongsToMany(Monitor::class, 'monitor_order_sectors')->wherePivot('is_active')->first();
    // }
    public function boss()
    {
        return $this->belongsTo(User::class, 'boss_id');
    }
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
    public function getBossNameAttribute(){
        return $this->boss->name ?? trans('translation.no-data');
    }
    public function getSupervisorNameAttribute(){
        return $this->supervisor->name ?? trans('translation.no-data');
    }
    public function getBossLabelAttribute(){
        if (config('app.use_monitor_code')) {
            return $this->boss->monitor->code ?? trans('translation.no-data');
        }
    
        return $this->boss->name ?? trans('translation.no-data');
    }
    public function getSupervisorLabelAttribute(){
        if (config('app.use_monitor_code')) {
            return $this->supervisor->monitor->code ?? trans('translation.no-data');
        }
    
        return $this->supervisor->name ?? trans('translation.no-data');
    }
    public function getMonitorsNameArrayAttribute(){

        return $this->order_sectors->isEmpty() ? null : $this->order_sectors->flatMap(function ($order_sector) {
            return $order_sector->monitor_order_sectors->isEmpty() ?: $order_sector->monitor_order_sectors->pluck('monitor.user.name');
        })->toArray();
    }
    public function getMonitorsNameWithPhoneArrayAttribute(){

        return $this->order_sectors->isEmpty() ? null : $this->order_sectors->flatMap(function ($order_sector) {
            return $order_sector->monitor_order_sectors->isEmpty() ?: $order_sector->monitor_order_sectors->pluck('monitor.user.name_with_phone');
        })->toArray();
    }

    public function getOrganizationNameAttribute()
    {

        return is_null($this->classification->organization) ? null : $this->classification->organization->name ?? trans('translation.no-organization');
    }


    public static function columnNames()
    {
        return array(
            'id' => 'id',
            'label' => 'sector-label',
            'provider' => 'provider',
            'sight' => 'sight',
            'guest_quantity' => 'guest-quantity',
            'classification.code' => 'classification',
            'organization.name' => 'organization',
            'manager_id' => 'manager_id',
            'boss_name' => 'boss',
            'supervisor_name' => 'supervisor',
            'monitors' => 'monitors',
            'nationality_organization.nationality.name' => 'nationality',
            // 'license_number' => 'license-number',
            'camp_number' =>  'camp-number',
            'block_number' => 'block-number',
            'kitchen_quantity' => 'kitchen-quantity',
            'track_package_number' => 'track-package-number',
            'note' => 'note',
            'location' => 'mina-location',
            'arafah_location'=> 'arafah_location',
            // 'location' => 'location',
            'order_code' => 'order-id',
            'action' => 'action',
            
        );
    }

    public static function columnInputs()
    {
        return array(
            'label' => 'text',
            'sight' => 'text',
            'guest_quantity' => 'number',
            // 'organization_id' => 'select',
            'classification_id' => 'select',
            'longitude' => 'number',
            'latitude' => 'number',
            'arafah_longitude' => 'number',
            'arafah_latitude' => 'number',
            'kitchen_quantity' => 'number',
            'nationality_organization_id' => 'select',
            'manager_id' => 'text',
            'location' => 'text',
            'boss_id' => 'select',
            'supervisor_id' => 'select',
            'license_number' => 'text',
            'camp_number' => 'text',
            'block_number' => 'text',
            'track_package_number' => 'text',
            'attachment' => 'file',
            'note' => 'text',
           

        );
    }

    public static function notRequiredColumns()
    {
        return array(
            'boss_id' => false,
            'supervisor_id' => false,
            'location' => false,
            'kitchen_quantity' => false,
            'note' => false,
            'latitude' => false,
            'longitude' => false,
            'arafah_longitude' => false,
            'arafah_latitude' => false,
            'sight_attachment' => false,
            'license_number' => false,
            'camp_number' => false,
            'block_number' => false,
            'track_package_number' => false,
            
           
        );
    }

    public static function columnOptions($organization = null)
    {
        $classification = Classification::query();
        if ($organization != null) {
            $classification = $classification->where('organization_id', $organization->id);
        }
        $nationalities = NationalityOrganization::with([
            'nationality:id,name,flag'
        ])->select('id', 'nationality_id', 'organization_id');
        if ($organization != null) {
            $nationalities = NationalityOrganization::with('nationality:id,flag,name')->where('organization_id', $organization->id);
        }
        $bosses = User::whereHas('roles', function ($q) {
            $q->whereIn('id', [Role::BOSS]);
        })->get()->pluck('name', 'id')->toArray();

        $supervisors = User::whereHas('roles', function ($q) {
            $q->whereIn('id', [Role::SUPERVISOR]);
        })->get()->pluck('name', 'id')->toArray();

        return array(
            'classification_id' => $classification->get()->pluck('code', 'id')->toArray(),
            'nationality_organization_id' => $nationalities->get()->pluck('nationality.name', 'id')->toArray(),
            'boss_id' => $bosses,
            'supervisor_id' => $supervisors,
            // 'organization_id' => Organization::all()->pluck('name_ar','id')->toArray(),
        );
    }

    public static function columnSubtextOptions($organization = null, $value = 'organization_name_with_price')
    {
        $classification = Classification::with('organization');
        if ($organization != null) {
            $classification = $classification->where('organization_id', $organization->id);
        }
        $nationalities = Nationality::query();
        if ($organization != null) {
            $nationalities = NationalityOrganization::where('organization_id', $organization->id);
        }
        return array(
            'classification_id' => $classification->get()->pluck($value, 'id')->toArray(),
            'nationality_organization_id' => $nationalities->get()->pluck('nationality.flag_icon', 'id')->toArray(),

        );
    }


    public static function filterColumns()
    {
        $users = User::with('roles')->role(['boss','supervisor','monitor'])->get();
        return array(
            'facility_id' => Facility::whereHas('orders')->get()->pluck('name','id')->toArray(),
            'classification_id' => Classification::whereHas('sectors')->get()->pluck('organization_name_and_code','id')->toArray(),
            'organization_id' => Organization::query()->get()->pluck('name','id')->toArray(),
            'boss_id' => $users->filter(fn($user) => $user->hasRole('boss'))->pluck('name','id')->toArray(), 
            'supervisor_id' => $users->filter(fn($user) => $user->hasRole('supervisor'))->pluck('name','id')->toArray(), 
            'monitor_id' => $users->filter(fn($user) => $user->hasRole('monitor'))->pluck('name','id')->toArray(), 
            'nationality_id' => Nationality::whereHas('nationality_organizations')->get()->pluck('name','id')->toArray(),

        );
    }

    public function getAttachmentUrlAttribute()
    {
        // dd(collect($this->attachments->last()));
        return $this->attachment_url_response_shape($this->attachments, $this);
    }

    public function scopeActiveOrderSectorByServiceId(Builder $query, $serviceId = 1 , $facility_id = null): void
    {
        $query->whereHas('order_sectors', function ($query) use ($serviceId, $facility_id) {
            $query->active()
                ->whereHas('order', function ($query) use ($serviceId, $facility_id) {
                    $query->whereHas('organization_service', function ($query) use ($serviceId) {
                        $query->whereHas('service', function ($query) use ($serviceId) {
                            $query->whereId($serviceId);
                        });
                    });

                    if($facility_id){
                        $query->whereIn('facility_id',$facility_id);
                    }
                });
        });
    }

    public function getArafahLocationAttribute()
    {
        return isset($this->arafah_latitude) && isset($this->arafah_longitude) ? 'https://www.google.com/maps?q=' . $this->arafah_longitude . ',' . $this->arafah_latitude : null;
    }
}
