<?php

namespace App\Http\Controllers;

use App\Http\Resources\WebResources\UserResource;
use Throwable;

use App\Models\User;
use App\Models\Audit;
use App\Models\Guest;
use Illuminate\Http\Request;
use App\Models\AttachmentLabel;
use App\Traits\AttachmentTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateUserRequest;
use App\Traits\SmsTrait;
use App\Traits\WhatsappTrait;

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
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show(Request $request, $id)
  {
    $user = User::findOrFail($id);
    if ($request->is('api/*')) {
      return response()->json(['user' => new UserResource($user)], 200);
    }
  }
  public function info(Request $request)
  {

    $user = User::findOrFail(auth('sanctum')->user()->id);
    // dd($user);
    $organization_ids = $user->favourit_organizations->pluck('id');
    $user->favourit_organizations_ids = $organization_ids ?? array();

    $user->setHidden(['roles', 'favourit_organizations']);

    if ($request->is('api/*')) {
      return response()->json(['user' => new UserResource($user)], 200);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function showByPhone(Request $request, $phone)
  {
    $user = User::where('phone', $phone)->first();
    if ($request->is('api/*')) {
      return response()->json(['user' => new UserResource($user)], 200);
    }
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(UpdateUserRequest $request)
  {

    $this->validateOrganization();

    $user = User::findOrFail(auth('sanctum')->user()->id);

    foreach ($request->all() as $key => $value) {
      if (in_array($key, $user->getFillable())) {
        $user[$key] = $value;
      }
    }

    if ($request->has('del_attachments')) {
      foreach ($request->del_attachments as $key => $value) {
        $attachment = AttachmentLabel::find($value);
        if ($attachment->is_required) {
          return response()->json(['message' => trans("translation.You can't delete :attribute attachment", ["attribute" => trans('validation.labels.' . $attachment->label)])], 400);
        }
        $this->delete_attachment($user, $attachment->id);
        // return response()->json(['message'=> trans("translation.:attribute attachment deleted successfully", ["attribute" => trans('validation.labels.' . $attachment->label)])],400);
      }
    }

    $flag = null;
    // if ($request->has('account_name') || $request->has('iban') || $request->has('bank_id')) {
    //   if (isset(request()->attachments[AttachmentLabel::USER_IBAN_LABEL])) {
    //     $user->iban()->update(
    //       request()->only(['account_name', 'iban', 'bank_id'])
    //     );
    //   } else {
    //     $flag = trans('translation., Bank infromation not updated due to missing attachment');
    //   }
    // }

    if ($request->has('attachments')) { //just in update check if theres and attachments in the request, if so validate it an update it ,, if not then theres no need for validation in the first place
      $this->attachments_validator($request->all(), $user)->validate();

      foreach ($request->attachments as $key => $value) {
        $this->update_attachment($value, $user, $attachment_label_id = $key, null, $user_id = $user->id);
      }
    }

    foreach ($request->all() as $key => $value) {
      if (in_array($key, $user->getFillable())) {
        $user[$key] = $value;
      }
    }
    $user->update();
    $message = trans('translation.send-whatsapp-update-user');
    $whatsapp_response = $this->send_message($this->getSender(), $message, $user->phone_code . $user->phone);
    $sending_sms = $this->send_sms($this->getSender(), $message, $user->phone, $user->phone_code);
    return response()->json(['message' => trans('translation.User updated successfully') . $flag, 'user' => new UserResource($user), 'whatsapp_response' => $whatsapp_response, 'sending_sms' => $sending_sms], 200);
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

  public function personal()
  {
    $user = User::find(auth()->user()->id);

    return view('users.personal', compact('user'));
  }

  public function personal_store(Request $request)
  {
    $user = User::find(auth()->user()->id)->update($request->only('name', 'email', 'gender', 'national_id', 'birthday', 'birthday_hj'));
    if ($user) {
      return redirect()->route('orders.index');
    }
    return back()->with(['danger' => "Have Errors"]);
  }

  public function userLogs()
  {
    try {
      $audits = Audit::where('user_id', auth()->user()->id)->get();
      return response()->json(["audits" => $audits], 200);
    } catch (Throwable $th) {
      return response()->json([
        'message' => $th->getMessage()
      ], 500);
    }
  }
}
