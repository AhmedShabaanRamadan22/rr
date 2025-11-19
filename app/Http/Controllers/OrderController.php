<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Answer;
use App\Models\Status;
use App\Models\Section;
use App\Models\Service;
use App\Models\Facility;
use App\Models\Question;
use App\Traits\SmsTrait;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Traits\WhatsappTrait;
use App\Traits\AttachmentTrait;
use App\Traits\OrganizationTrait;
use App\Http\Requests\OrderRequest;
use App\Models\OrganizationService;
use App\Http\Controllers\Controller;
use App\Events\ChartEvent;
use App\Http\Resources\WebResources\OrderResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderController extends Controller
{

  use OrganizationTrait, WhatsappTrait, AttachmentTrait, SmsTrait;
  protected $status_id_default = Status::NEW_ORDER;
  protected $interview_status_id_default = Status::NEW_INTERVIEW;
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    $this->validateOrganization();

    $user = auth('sanctum')->user();
    $organization = $this->getOrganization();
    // $user_orders = $user->orders;
    $user_orders = $user->orders()->whereHas('organization_service',function($q) use ($organization){
      $q->where('organization_id',$organization->id);
    })->orderByDesc('created_at')->get();
    //return count($user_orders);
    $all_user_orders = [];


    
    // foreach ($user_orders as $order) {
    //   if ($order->organization->id == $organization->id) {
    //     $all_user_orders[] = $order->getOrder($order);
    //   }
    // }

    // return response()->json(['all_user_orders' => OrderResource::collection($all_user_orders)], 200);
    return response()->json(['all_user_orders' => OrderResource::collection($user_orders)], 200);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    $organization_service = OrganizationService::findorFail(request()->organization_service_id);
    $questions = $organization_service->questions;
    foreach ($questions as $question) {
      $question = $question->options;
    }
    return response(compact('questions'), 200);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(OrderRequest $request)
  {
    $this->validateOrganization();
    if(($this->getOrganization()->close_order)){
      return response(['message' => trans('translation.Order is closed for this organization')], 401);
    }
    $user = User::findOrFail(auth('sanctum')->user()->id);

    if (Order::where([
      'user_id' => auth()->user()->id,
      'organization_service_id' => $request->input('organization_service_id'),
      'facility_id' => $request->input('facility_id')
    ])->whereNotIn('status_id', [Status::CANCELED_ORDER, Status::REJECTED_ORDER])->exists()) {
      return response(['message' => trans('translation.Order-already-exists')], 400);
    }

    $order = $user->orders()->create(
      $request->only(['organization_service_id', 'facility_id'])
      + ['status_id' => $this->status_id_default,'interview_status_id',$this->interview_status_id_default]
    );

    if($request->has('country_ids')){
      $order->update([
        'country_ids' => $request->input('country_ids'),
      ]);
    }

    if ($request->has('attachments')) {
      $this->attachments_validator($request->all(), 'orders')->validate();
      foreach ($request->attachments as $key => $attachment) {
        $new_attachment = $this->store_attachment($attachment, $order, $key, null, $user->id);
      }
    }

    if (request()->has('answers')) {
      foreach (request()->answers as $question_id => $answer) {
        $order->answers()->create([
          'user_id' => $user->id ?? 0,
          //'order_id' => $order->id ?? 0,
          'question_id' => $question_id,
          'value' => is_array($answer) ? implode(",", $answer) : $answer,
        ]);
      }
    }
    $message = trans('translation.send-whatsapp-add-new-order', ['order_code' => $order->code,'service_name' => $order->organization_service->service->name,'facility_name' => $order->facility->name,'providor_name' => $order->user->name]);
    $whatsapp_response = $this->send_message($this->getSender(), $message, $user->phone_code . $user->phone);
    $sending_sms = $this->send_sms($this->getSender(), $message,$user->phone,$user->phone_code);
    return response()->json(['order' => new OrderResource($order), 'whatsapp_response' => $whatsapp_response, 'sending_sms' => $sending_sms], 200);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show(Order $order)
  {
    //!! check first if the auth not admin
    // dd($order, (auth('sanctum')->user()->id ?? 0));
    if ($order->user_id != (auth('sanctum')->user()->id ?? 0)) {
      return response(['message' => trans('transaltion.This action is unauthorized.')], 403);
    }
    $order = $order->getOrder($order);
    return response()->json(['order' => new OrderResource($order)], 200);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit(Request $request, $id)
  {
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(Request $request, Order $order, $key = null, $value = null, $url = null)
  {
    if ($order->user_id != (auth()->user()->id ?? 0)) {
      return response(['message' => trans('transaltion.This action is unauthorized.')], 403);
    }
    $order->update(["$key" => $value]);

    if ($url != null) {
      return redirect($url);
    }
  }
  public function update_note(Request $request)
  {
    $order = Order::findOrFail($request->order_id);
    $this->update($request, $order, 'note', $request->note);
  }

  public function change_status($request, $status, Order $order)
  {
    $this->update($request, $order, 'status_id', $status);
  }

  public function cancel(Request $request, Order $order)
  {
    $result = "";
    // $order = Order::find(request()->order_id);
    // cancel status id = 5
    if (!in_array($order->status_id, [Status::CANCELED_ORDER]) && $order->user_id == auth()->user()->id) {
      $result = $this->change_status($request, Status::CANCELED_ORDER, $order);
      $message = trans('translation.send-whatsapp-cancel-order', ['order_code' => $order->code,'service_name' => $order->organization_service->service->name,'facility_name' => $order->facility->name,'providor_name' => $order->user->name]);
      $whatsapp_response = $this->send_message($this->getSender(), $message, $order->user->phone_code . $order->user->phone);
      $sending_sms = $this->send_sms($this->getSender(), $message,$order->user->phone,$order->user->phone_code);
      return response()->json(['success' => "Order ($order->id) has been canceled", 'result' => $result, 'whatsapp_response' => $whatsapp_response, 'sending_sms' => $sending_sms], 200);
    }
    else{
      return response()->json(['message' => 'order already canceled'], 400);
    }

  }

  public function update_status(Request $request)
  {
    $order = Order::find($request->order_id);
    $this->change_status($request, $request->status_id, $order);

    return response()->json(['message' => 'save status successful'], 200);
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
}
