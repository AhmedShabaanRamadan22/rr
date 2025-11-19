<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\Audit;
use App\Models\Country;
use App\Traits\AttachmentTrait;
use App\Traits\BaseNotifiable;
use App\Traits\RoleAssignerTrait;
// use Spatie\Permission\Models\Role;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail, Auditable
{
    use HasApiTokens, HasFactory, BaseNotifiable, AttachmentTrait, SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use HasRoles {
        assignRole as protected originalAssignRole;
    }

    /*
 $permission->assignRole($role);
 $permission->syncRoles($roles);
 $permission->removeRole($role);
 */

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */



    protected $fillable = [
        'name',
        'email',
        'phone',
        'phone_code',
        'nationality',
        'national_id',
        'national_id_expired',
        'birthday',
        'birthday_hj',
        'national_id_expired_hj',
        'password',
        'verified_at',
        'organization_id',
        'email_verified_at',
        'national_source',
        'bravo_id',
        'salary',
        'address',
        'scrub_size'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        // 'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    // protected $appends =  [
    //     "nationality_name",
    //     "national_source_name",
    //     "role_name",
    //     "role_ids_array",
    //     "is_verified",
    //     "is_organization",
    //     "is_organization_admin",
    //     "is_super_admin",
    //     'age',
    //     'national_id_attachment',
    //     'profile_photo',
    //     'attachmentUrl',
    //     'bank_information',
    //     'monitor_position',
    // ];



    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }
    // public function attachment()
    // {
    //     return $this->morphOne(Attachment::class, 'attachmentable');
    // }
    // public function organization_user()
    // {
    //     return $this->hasMany(OrganizationUser::class);
    // }
    // public function organizations()
    // {
    //     return $this->belongsToMany(Organization::class,'organization_user');
    // }

    public function iban()
    {
        return $this->morphOne(Iban::class, 'ibanable');
    }
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    public function bravo()
    {
        return $this->belongsTo(Bravo::class);
    }
    public function sector()
    {
        return $this->hasOne(Sector::class);
    }
    public function supervisor_sectors()
    {
        return $this->hasMany(Sector::class,'supervisor_id');
    }
    public function boss_sectors()
    {
        return $this->hasMany(Sector::class,'boss_id');
    }
    public function favourit_organizations()
    {
        return $this->belongsToMany(Organization::class, 'favourit_organization');
    }
    public function favourit_organizations_pivot()
    {
        return $this->hasMany(FavouritOrganization::class);
    }
    // public function favourit_organizations(){
    //     return $this->hasMany(FavouritOrganization::class);
    // }

    public function assists()
    {
        return $this->hasMany(Assist::class);
    }
    public function supports()
    {
        return $this->hasMany(Support::class);
    }
    public function monitor_order_sectors()
    {
        return $this->hasMany(MonitorOrderSector::class);
    }
    public function contract()
    {
        return $this->morphOne(Contract::class, 'contractable')->latest();
    }
    public function contracts()
    {
        return $this->morphMany(Contract::class, 'contractable');
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function facilities()
    {
        return $this->hasMany(Facility::class);
    }
    public function facility_employees()
    {
        return $this->hasManyThrough(FacilityEmployee::class, Facility::class, 'user_id', 'facility_id', 'id', 'id');
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    public function monitor()
    {
        return $this->hasOne(Monitor::class);
    }
    public function track_locations()
    {
        return $this->hasMany(TrackLocation::class);
    }
    public function otps()
    {
        return $this->hasMany(Otp::class);
    }
    public function lastOtp()
    {
        return $this->hasOne(Otp::class)->latest();
    }
    public function isExpired()
    {
        return Carbon::now()->isAfter($this->expire_at);
    }
    public function profile_photo_attachment()
    {
        return $this->morphOne(Attachment::class, 'attachmentable')->where('attachment_label_id', AttachmentLabel::PROFILE_PHOTO_LABEL)->latest('created_at');
    }
    public function getProfilePhotoAttribute()
    {
        return $this->profile_photo_attachment->url ?? asset('build/images/users/32/person.png');
    }
    public function national_id_photo()
    {
        return $this->morphOne(Attachment::class, 'attachmentable')->where('attachment_label_id', AttachmentLabel::NATIONAL_ID_LABEL)->latest('created_at');
    }
    public function getNationalIdAttachmentAttribute()
    {
        return $this->national_id_photo->url ?? null;
    }

    /**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function hasVerifiedEmail()
    {
        return !is_null($this->verified_at);
    }

    public function getRoleNameAttribute()
    {
        return $this->roles->implode('name', ',');
    }

    public function getRoleIdsArrayAttribute()
    {
        return $this->roles->pluck('id')->toArray();
    }

    public function getIsVerifiedAttribute()
    {
        return !is_null($this->verified_at);
    }
    public function getAgeAttribute()
    {
        return !is_null($this->birthday) ? Carbon::parse($this->birthday):null;
    }

    public function getIsOrganizationAdminAttribute()
    {
        return $this->hasRole(['organization admin']);
    }

    public function getIsOrganizationAttribute()
    {
        return $this->hasRole(['organization admin', 'organization employee']);
    }
    public function getIsSuperAdminAttribute()
    {
        return $this->hasRole(['superadmin']);
    }
    public function getIsBossOrSupervisorAttribute()
    {
        return $this->hasRole(['boss','supervisor']);
    }
    public function getAttachmentUrlAttribute()
    {
        return $this->attachment_url_response_shape($this->attachments, $this);
    }

    public function getNationalityNameAttribute()
    {
        $this->setHidden(['country']);
        return $this->country->name ?? "";
    }

    public function getNationalSourceNameAttribute()
    {
        $this->setHidden(['city']);
        return $this->national_source_city->name ?? "";
    }
    public function getMonitorPositionAttribute()
    {
        if ($this->hasRole('boss')) return 'رئيس تشغيلي';
        if ($this->hasRole('supervisor')) return 'مشرف تشغيلي';
        if ($this->hasRole('monitor')) return 'مراقب';
        return null;
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'nationality');
    }
    public function canChangeTicketStatus()
    {
        return $this->hasDirectPermission('change_ticket_status_from_closed_to_inProgress');
    }
    public function canChangeOrderStatus()
    {
        return $this->hasAnyPermission([
            'change_order_status_from_rejected_to_new',
            'change_order_status_from_rejected_to_inProgress',
            'change_order_status_from_rejected_to_processing',
            'change_order_status_from_rejected_to_confirmed',
            'change_order_status_from_rejected_to_qualified',
            'change_order_status_from_rejected_to_accepted',
            'change_order_status_from_accepted_to_new',
            'change_order_status_from_accepted_to_qualified',
            'change_order_status_from_accepted_to_inProgress',
            'change_order_status_from_accepted_to_confirmed',
            'change_order_status_from_accepted_to_processing',
            'change_order_status_from_accepted_to_rejected',
        ]);
    }
    public function checkTicketStatusPermissions($new_status)
    {
        if ($new_status == Status::IN_PROGRESS_TICKET) {
            return $this->hasPermissionTo('change_ticket_status_from_closed_to_inProgress');
        }
        return false;
    }
    public function checkOrderStatusPermissions($new_status, $old_status)
    {
        $new = Status::find($new_status)->name_en;
        $old = Status::find($old_status)->name_en;
        // dd($this->getAllPermissions()->implode('name', "
        // "));
        // dd($this->hasPermissionTo('change_order_status_from_' . strtolower($old) . '_to_' . strtolower($new)));
        return true;//$this->hasPermissionTo('change_order_status_from_' . strtolower($old) . '_to_' . strtolower($new));
    }
    public static function columnNames()
    {
        return array('id', 'user-name', 'user-phone-num', 'email', 'national-id', 'role-name', 'organization-name', 'bravo-number', 'bravo-code', 'action',);
    }
    public static function columnInputs()
    {
        return array(
            'contractable_id' => 'select',
            'salary' => 'number',
        );
    }
    public static function contractsColumnNames()
    {
        return array(
            'id' => 'id',
            'name' => 'user-name',
            'phone' => 'user-phone-num',
            'national_id' => 'national-id',
            'role_name' => 'role-name',
            'bravo.number' => 'bravo-number',
            'bravo.code' => 'bravo-code',
            'scrub_size' => 'scrub-size',
            'salary' => 'salary',
            'action' => 'contract-actions'
        );
    }
    public static function columnOptions()
    {
        $monitors = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['monitor', 'employee']);
        })->whereDoesntHave('contract');
        $roles = Role::query();
        return array(
            'nationality' => Country::all()->pluck('name', 'id')->toArray(),
            'all_roles' => $roles->pluck('name', 'id')->toArray(),
            'role_name' => $roles->whereNotIn('id', [Role::PROVIDOR, Role::MONITOR, Role::BOSS, Role::SUPERVISOR])->pluck('name', 'id')->toArray(),//Role::all()->pluck('name', 'id')->toArray(),
            'organizations' => Organization::all()->pluck('name', 'id')->toArray(),
            'bravo_number' => Bravo::all()->pluck('name', 'id')->toArray(),
            'contractable_id' => $monitors->pluck('name', 'id')->toArray(),
            'salary' => $monitors->pluck('salary', 'id')->toArray(),
            'scrub_size' => ['s' => 'small',
                             'm' => 'medium',
                             'l' => 'large',
                             'xl' => 'x-large',
                             '2xl' => 'xx-large',
                             '3xl' => 'xxx-large',
                             '4xl' => 'xxxx-large',
                             '5xl' => 'xxxxx-large' ],
            'national_source' => City::all()->pluck('name','id')->toArray()
        );
    }

    public function national_source_city()
    {
        return $this->hasOne(City::class, 'id', 'national_source');
    }

    public function getBankInformationAttribute()
    {
        return $this->iban()->latest()->first() ?? '';
    }

    public function getNameWithPhoneAttribute()
    {
        return ($this->name ?? "-") . ': ' . ($this->phone ?? "-");
    }

    public function assignRole($roles)
    {
        // dd($roles, is_array($roles), Role::where('name', $roles)->first()->name);
        if(!is_null($roles)){
            $role_names = !(is_array($roles)) ? [Role::where('name', $roles)->first()->name] : Role::whereIn('id', array_values($roles))->pluck('name')->toArray();
            foreach($role_names as $role_name){
                if ($this->hasRole('providor') && $role_name !== 'sector manager') {
                    return response()->json(['message'=> trans("translation.Providers can only assigned to 'sector manager'") ,'alert-type'=>'error'], 400);
                }
                if ($this->hasRole('monitor') && !in_array($role_name, ['supervisor', 'boss'])) {
                    return response()->json(['message'=>  trans("translation.Monitor can only assigned to 'supervisor, boss'"),'alert-type'=>'error'], 400);
                }
                if ($this->hasRole('organization chairman')) {
                    return response()->json(['message'=> trans("translation.Organization chairman can not be assigned to any other roles."),'alert-type'=>'error'], 400);
                }
                // Call the original assignRole() method from Spatie Permission package
                $this->originalAssignRole($role_name);
            }
        }
    }

    //* assignable
    public function assigns_by()
    {
        return $this->hasMany(Assign::class, 'assigner_id');
    }

    // Users who were assigned something
    public function assigns_to()
    {
        return $this->hasMany(Assign::class, 'assignee_id');
    }

    public function assignedByType($type)
    {
        return $this->hasMany(Assign::class, 'assigner_id')
                    ->where('assignable_type', $type);
    }

    public function assignedToType($type)
    {
        return $this->hasMany(Assign::class, 'assignee_id')
                    ->where('assignable_type', $type);
    }

    public function assigns_to_orders()
    {
        return $this->assignedToType(Order::class);
    }

    public function assigned_orders()
    {
        return $this->morphedByMany(Order::class, 'assignable', 'assigns', 'assignee_id', 'assignable_id')->whereNull('assigns.deleted_at');
    }
}
