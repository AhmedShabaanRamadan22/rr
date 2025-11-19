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

class ProviderController extends Controller
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
    $users = User::with('national_source_city:id,name_ar,name_en')->whereHas('roles', function ($query) {
      $query->whereIn('name', ['providor']);
    });
    // $organizations = Organization::all();
    // $countries = Country::all();
    // $roles = Role::all();
    $columns = array(
      'id' => 'id', 
      'user-name' => 'user-name', 
      'user-phone-num' => 'user-phone-num', 
      'email' => 'email', 
      'national-id' => 'national-id', 
      'facility_name' => 'facility-name', 
      'organization-name-registered' => 'organization-name-registered', 
      'action' => 'action',
    );
    $organizations = Organization::select('id','name_ar','name_en')->get();
    // $bravos = Bravo::all();
    return view('admin.providers.index', compact('columns','organizations'));
}


// public function edit(User $user)
// {
//     if($user->hasAnyRole(['monitor','providor'])){
//       $roles = [];
//     }
//     else{
//       $roles = Role::whereNotIn('id', [Role::PROVIDOR, Role::MONITOR, Role::BOSS, Role::SUPERVISOR])->get();
//     }
//     $permissions = Permission::all();
//     $countries = Country::all();
//     $organizations = Organization::all();
//     $favourite_organizations = $user->favourit_organizations->pluck('id')->toArray();
//     $user_roles = explode(',', $user->role_name);
//     $columnOptions = User::columnOptions();
//     $bravos = Bravo::all();
//     $attachments = AttachmentLabel::where('type', 'users')->get();
//     return view('admin.providers.edit', compact('user', 'roles', 'permissions', 'countries', 'organizations', 'favourite_organizations', 'user_roles', 'columnOptions','bravos', 'attachments'));
// }

//   public function update(Request $request, $id){
//     // dd($request->all());
//     $user = User::findOrFail($id);
//     // dd($request->favourite_organizations);

//     $existed_user = User::whereNot('id', $id)->where(function($query) use ($request){
//       $query = $query->where('email', $request->email)->orwhere('phone', $request->phone)->orwhere('national_id', $request->national_id);
//     })
//     ->first();
//     if ($existed_user) {
//       return back()->with(array('message' => trans('translation.user-exist'), 'alert-type' => 'error'));
//     }

//     $user->update([
//       'name'=> $request->name,
//       'email'=> $request->email,
//       'nationality' => $request->nationality,
//       'birthday'=> $request->birthday,
//       'birthday_hj'=> $request->birthday_hj,
//       'phone'=> $request->phone,
//       'scrub_size'=> $request->scrub_size,
//       'address'=> $request->address,
//       'national_source'=> $request->national_source,
//     ]);

//     $user->favourit_organizations()->sync($request->favourite_organizations);
//     if(isset($request->password) && $request->user()->can('edit_user_password')){
//       $user->update([
//         'password' => Hash::make($request->password),
//       ]);
//     }
//     if(isset($request->national_id) && $request->user()->can('edit_user_nationalID')){
//       $user->update([
//         'national_id' => $request->national_id,
//       ]);
//     }
//     if(isset($request->national_id_expired)){
//       $user->update([
//         'national_id_expired' => $request->national_id_expired,
//       ]);
//     }

//     if(isset($request->organization)){
//         $user->update([
//           'organization_id' => $request->organization,
//         ]);
//       }
//     if (isset($request->profile_photo)) {
//       $this->update_attachment($request->profile_photo, $user, AttachmentLabel::PROFILE_PHOTO_LABEL);
//     }
//     if (isset($request->national_id_attachment)) {
//       $this->update_attachment($request->national_id_attachment, $user, AttachmentLabel::NATIONAL_ID_LABEL);
//     }
//     if(isset($request->role) && $request->user()->can('edit_user_role')){
//       $new_roles = array_diff($request->role, $user->role_ids_array);
//       $removed_roles = array_diff($user->role_ids_array, $request->role);
//       foreach($removed_roles as $removed_role){
//         $user->removeRole($removed_role);
//       }
//       $err = $user->assignRole($new_roles);
//       if($err){
//         return back()->with(array('message' => $err->getData()->message, 'alert-type' =>  $err->getData()->{'alert-type'}));
//       }
//     }
//     if(!$request->has('permission_filter')){
//       $user->permissions()->detach();
//     }
//     $user->syncPermissions($request->permission_filter);

//     if(isset($request->bravo)){
//       $selectedBravo = Bravo::find($request->bravo) ?? null;

//       if ($selectedBravo) {
//           $currentBravoUser = $selectedBravo->user;

//           if ($currentBravoUser) {
//               if ($user->bravo_id != $selectedBravo->id) {
//                   $currentBravoUser->update(['bravo_id'=>null]);
//               }
//           }

//           $user->update(['bravo_id'=> $selectedBravo->id]);
//           $selectedBravo->update(['given_id'=>auth()->user()->id??0]);

//       }

//     // if ($selectedBravo) {
//     //     $currentBravoUser = $selectedBravo->user;

//     //     if ($user->bravo) {
//     //         if ($currentBravoUser) {
//     //             $currentBravoUser->bravo_id = $user->bravo_id;
//     //             $currentBravoUser->save();
//     //             $selectedBravo->given_id = auth()->id();
//     //             $selectedBravo->save();
//     //         }
//     //     } else {
//     //         if ($currentBravoUser) {
//     //             $currentBravoUser->bravo_id = null;
//     //             $currentBravoUser->save();

//     //         }
//     //     }
//     //     $user->bravo_id = $request->bravo;
//     // } else if ($user->bravo_id) {
//     //     $user->bravo_id = null;
//     // }
//     // $user->save();
//     // $selectedBravo->given_id = auth()->id();
//     // $selectedBravo->save();
//     }
// //    event(new CrudOperationEvent());
//     return redirect()->route('users.index')->with(array('message' => trans("translation.user updated successfully"), 'alert-type' => 'success'));


//   }


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

    $is_admin = auth()->user() != null ? auth()->user()->hasRole('admin') : false;

    $query = User::with(
      'organization:id,name_en,name_ar',
      'roles:id,name',
      'bravo:id,number,code',
    );

    $query->whereHas('roles', function ($q) {
      $q->whereIn('role_id',[Role::PROVIDOR]);
    });

    if (\request('organization_id')) {
        $query->whereIn('organization_id', \request('organization_id'));
    }

    if($is_admin){
      $query->whereHas('orders',function($q){
          $q->assignee(auth()->user());
      });
    }

    return datatables($query->orderByDesc('created_at')->get())

      ->editColumn('organization_name', function (User $user) {
        if($user->organization != null){
          return '<span class="badge bg-primary">' . $user->organization->name . '</span>';
        }
        return trans("translation.no-selected-organization");
        // return $user->organization->name ?? '--';
      })
      ->editColumn('facility_name', function(User $user){
        return $user->facilities->implode('name', ',');
      })
      ->editColumn('phone', function (User $user) {
        return '<a href="https://api.whatsapp.com/send?phone=966' . $user->phone . '" target="_blank">' . $user->phone . '</a>';
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
      ->rawColumns(['phone', 'action', 'organization_name'])
      ->toJson();
  }
}
