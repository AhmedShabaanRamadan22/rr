<?php

namespace App\Http\Controllers\Admin;

use Hash;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Bravo;
use App\Models\Guest;
use App\Models\Country;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Traits\WhatsappTrait;
use App\Models\AttachmentLabel;
use App\Traits\AttachmentTrait;
use App\Services\ContractService;
use App\Http\Controllers\Controller;
use App\Models\FavouritOrganization;
use App\Traits\SmsTrait;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{

  use AttachmentTrait, WhatsappTrait, SmsTrait;
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {

    // <td>{{$item->national_id}}</td>
    // $data = User::with('organizations')->get(['users.name', 'users.phone', 'users.email', 'users.national_id','users.role_name', 'organization_id']);
    $users = User::with('national_source_city:id,name_ar,name_en');
    // $organizations = Organization::all();
    // $countries = Country::all();
    // $roles = Role::all();
    $columns = User::columnNames();
    $columnOptions = User::columnOptions();
    // $bravos = Bravo::all();
    $required_attachments = AttachmentLabel::where('type', 'users')->whereNot('id', AttachmentLabel::USER_IBAN_LABEL)->get();
    return view('admin.users.index', compact('users', 'columns', 'columnOptions','required_attachments'));
}

/**
 * Show the form for creating a new resource.
 *
 * @return Response
 */
public function create()
{
    //
}

/**
 * Store a newly created resource in storage.
 *
 * @return Response
 */
public function store(Request $request)
{
    // dd($request->all());
    if (User::where('national_id', $request->national_id)->first() ||
    User::where('phone', $request->phone)->first() ||
    User::where('email', $request->email)->first()) {
        return back()->with(array('message' => trans('translation.user-exist'), 'alert-type' => 'error'));
    }
    // dd( );
    $providor = Role::all()->where('name', 'providor')->first();
    $password = $request->password == null ? Hash::make('Rakaya2023'): Hash::make($request->password);
    $salary = $request->salary == null ? 5000 : $request->salary;
    $is_providor = $request->role == $providor->id;
    $new_user = User::create($request->except(['password', 'salary']) + [
        'phone_code' => '+966',
        'password'=> $password,
        'salary' => $salary,
        'verified_at'=> $is_providor ? null : Carbon::now()
    ]);
    if ($request->has('role')) {
      $new_user->assignRole($request->role);
    }
    if ($request->has('attachments')) {
      foreach ($request->attachments as $key => $attachment) {
          $this->store_attachment($attachment, $new_user, $key, null, $new_user->id);
      }
  }
    $user = Auth()->user();
    $message = trans('translation.send-whatsapp-add-new-user', ['user'=>$user->name, 'new_user'=>$request->name]);
    $whatsapp_response = $this->send_message($user->organization->sender??null, $message, $user->phone_code . $user->phone);
    $sending_sms = $this->send_sms($user->organization->sender??null, $message, $user->phone, $user->phone_code);
    return back()->with(array('message' => trans('translation.Added successfully'), 'alert-type' => 'success'));
}

/**
 * Display the specified resource.
 *
 * @param  int  $id
 * @return Response
 */
public function show(Request $request, $id)
{
    //
}


/**
 * Show the form for editing the specified resource.
 *
 * @param  int  $id
 * @return Response
 */
public function edit(User $user)
{
  $this->authorize('edit', $user);
  if($user->hasAnyRole(['monitor','providor'])){
    $roles = [];
  }
  else{
    $roles = Role::whereNotIn('id', [Role::PROVIDOR, Role::MONITOR, Role::BOSS, Role::SUPERVISOR])->get();
  }
  $permissions = Permission::all();
  $countries = Country::all();
  $organizations = Organization::all();
  $favourite_organizations = $user->favourit_organizations->pluck('id')->toArray();
  $user_roles = explode(',', $user->role_name);
  $columnOptions = User::columnOptions();
  $bravos = Bravo::all();
  $attachments = AttachmentLabel::where('type', 'users')->get();
  return view('admin.users.edit', compact('user', 'roles', 'permissions', 'countries', 'organizations', 'favourite_organizations', 'user_roles', 'columnOptions','bravos', 'attachments'));
}

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(Request $request, $id){
    // dd($request->all());
    $user = User::findOrFail($id);
    // dd($request->favourite_organizations);

    $existed_user = User::whereNot('id', $id)->where(function($query) use ($request){
      $query = $query->where('email', $request->email)->orwhere('phone', $request->phone)->orwhere('national_id', $request->national_id);
    })
    ->first();
    if ($existed_user) {
      return back()->with(array('message' => trans('translation.user-exist'), 'alert-type' => 'error'));
    }

    $user->update([
      'name'=> $request->name,
      'email'=> $request->email,
      'nationality' => $request->nationality,
      'birthday'=> $request->birthday,
      'birthday_hj'=> $request->birthday_hj,
      'phone'=> $request->phone,
      'scrub_size'=> $request->scrub_size,
      'address'=> $request->address,
      'national_source'=> $request->national_source,
    ]);

    $user->favourit_organizations()->sync($request->favourite_organizations);
    if(isset($request->password) && $request->user()->can('edit_user_password')){
      $user->update([
        'password' => Hash::make($request->password),
      ]);
    }
    if(isset($request->national_id) && $request->user()->can('edit_user_nationalID')){
      $user->update([
        'national_id' => $request->national_id,
      ]);
    }
    if(isset($request->national_id_expired)){
      $user->update([
        'national_id_expired' => $request->national_id_expired,
        'national_id_expired_hj' => $request->national_id_expired_hj,
      ]);
    }

    if(isset($request->organization)){
        $user->update([
          'organization_id' => $request->organization,
        ]);
      }
    if (isset($request->profile_photo)) {
      $this->update_attachment($request->profile_photo, $user, AttachmentLabel::PROFILE_PHOTO_LABEL);
    }
    if (isset($request->national_id_attachment)) {
      $this->update_attachment($request->national_id_attachment, $user, AttachmentLabel::NATIONAL_ID_LABEL);
    }
    if(isset($request->role) && $request->user()->can('edit_user_role')){
      $new_roles = array_diff($request->role, $user->role_ids_array);
      $removed_roles = array_diff($user->role_ids_array, $request->role);
      foreach($removed_roles as $removed_role){
        $user->removeRole($removed_role);
      }
      $err = $user->assignRole($new_roles);
      if($err){
        return back()->with(array('message' => $err->getData()->message, 'alert-type' =>  $err->getData()->{'alert-type'}));
      }
    }
    if(!$request->has('permission_filter')){
      $user->permissions()->detach();
    }
    $user->syncPermissions($request->permission_filter);

    if(isset($request->bravo)){
      $selectedBravo = Bravo::find($request->bravo) ?? null;

      if ($selectedBravo) {
          $currentBravoUser = $selectedBravo->user;

          if ($currentBravoUser) {
              if ($user->bravo_id != $selectedBravo->id) {
                  $currentBravoUser->update(['bravo_id'=>null]);
              }
          }

          $user->update(['bravo_id'=> $selectedBravo->id]);
          $selectedBravo->update(['given_id'=>auth()->user()->id??0]);

      }

    // if ($selectedBravo) {
    //     $currentBravoUser = $selectedBravo->user;

    //     if ($user->bravo) {
    //         if ($currentBravoUser) {
    //             $currentBravoUser->bravo_id = $user->bravo_id;
    //             $currentBravoUser->save();
    //             $selectedBravo->given_id = auth()->id();
    //             $selectedBravo->save();
    //         }
    //     } else {
    //         if ($currentBravoUser) {
    //             $currentBravoUser->bravo_id = null;
    //             $currentBravoUser->save();

    //         }
    //     }
    //     $user->bravo_id = $request->bravo;
    // } else if ($user->bravo_id) {
    //     $user->bravo_id = null;
    // }
    // $user->save();
    // $selectedBravo->given_id = auth()->id();
    // $selectedBravo->save();
    }
//    event(new CrudOperationEvent());
    return back()->with(array('message' => trans("translation.user updated successfully"), 'alert-type' => 'success'));
    // return redirect()->route('users.index')->with(array('message' => trans("translation.user updated successfully"), 'alert-type' => 'success'));


  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
  }

  public function employeesDataTable(Request $request)
  {
    $query = User::with('contracts', 'organization', 'bravo')->whereHas('contracts', function ($q) use ($request){
      $q->where('contractable_type', 'App\\Models\\User')->whereHas('contract_template', function($q2) use ($request){
        $q2->where('organization_id', $request->organization_id);
      });
    });

    return datatables($query->orderByDesc('created_at')->get())

      ->editColumn('role_name', function (User $user) {
        return $user->role_name?? trans('translation.no-data');
      })
      ->editColumn('bravo.number', function (User $user) {
        return $user->bravo->number ?? trans('translation.no-data');
      })
      ->editColumn('bravo.code', function (User $user) {
        return $user->bravo->code ?? trans('translation.no-data');
      })
      ->editColumn('scrub_size', function (User $user) {
        return $user->scrub_size ?? trans('translation.no-data');
      })
      ->editColumn('action', function (User $user) use ($request){
        $contract = (new ContractService())->find_contract($user->id, 'employee_' . $request->organization_id);
        return ' <a
            class="btn btn-outline-secondary btn-sm m-1 on-default " target="_blank"
            href="' . $contract->attachment->url . '"
            ><i class="mdi mdi-eye"></i>
          </a>
          <a href="'. (route('admin.contracts.download', ['contractable_id' => $user->id, 'contractable_type' => 'App\Models\User', 'contract_template' => $contract->contract_template->type])). '" class="btn btn-outline-primary btn-sm m-1  on-default m-r-5 ">
                <i class="mdi mdi-file-download-outline"></i>
            </a>
          <button type="button" class="btn btn-outline-danger btn-sm m-1  on-default m-r-5 delete-employee-contract" data-contract-id="' . $contract->id . '">
                <i class="mdi mdi-trash-can-outline"></i>
            </button>';
      })
      ->rawColumns(['action'])
      ->toJson();
  }
  public function dataTable(Request $request)
  {
    $query = User::with(
      'organization:id,name_en,name_ar',
      'roles:id,name',
      'bravo:id,number,code',
    );

    if (\request('role_id')) {
      $query->whereHas('roles', function ($q) {
        $q->whereIn('role_id', \request('role_id'));
      });
    }

    if (\request('organization_id')) {
        $query->whereIn('organization_id', \request('organization_id'));
    }
    return datatables($query->orderByDesc('created_at')->get())

      ->editColumn('organization_name', function (User $user) {
        if($user->organization != null){
          return '<span class="badge bg-primary">' . $user->organization->name . '</span>';
        }
        return trans("translation.no-selected-organization");
        // return $user->organization->name ?? '--';
      })
      ->editColumn('role_name', function(User $user){
        return $user->roles->implode('name', ',');
      })
      ->editColumn('phone', function (User $user) {
        return '<a href="https://api.whatsapp.com/send?phone=966' . $user->phone . '" target="_blank">' . $user->phone . '</a>';
      })
      ->addColumn('bravo-number', function (User $user) {
        if($user->bravo != null){
            return $user->bravo->number;
          }
          return '-';
      })
      ->addColumn('bravo-code', function (User $user) {
        if($user->bravo != null){
            return $user->bravo->code;
          }
          return '-';
      })
      ->editColumn('action', function (User $user) {
        $map = $user->hasRole('monitor') ? '<a href="" class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 " data-user-id="' . $user->id . '" data-bs-target="#userMap" data-bs-toggle="modal">
                <i class="mdi mdi-map"></i>
            </a>' : '';
        return ' <a
            class="btn btn-outline-secondary btn-sm m-1 on-default "
            href="' . (route('users.edit', $user->id)) . '"
            ><i class="mdi mdi-clipboard-edit-outline"></i>
          </a>' . $map;
      })
      ->rawColumns(['phone', 'action', 'organization_name','bravo-code', 'bravo-number'])
      ->toJson();
  }


  public function getUserTrackLocation($user_id){
    $user = User::with('track_locations')->findOrFail($user_id)->append('profile_photo');

    return response(['user' => $user],200);
  }
}
