<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Option;
use App\Models\Order;
use App\Models\Organization;
use App\Models\Status;
use App\Models\User;
use App\Services\AssignService;
use App\Services\SessionFlashService;
use Illuminate\Http\Request;

class OrderAssignController extends Controller
{
  protected AssignService $assignService;

  public function __construct(AssignService $assignService)
  {
      $this->assignService = $assignService;
  }

  public function index(){

      $domain = '';
      $organizations = Organization::all();
      $statuses = Status::order_statuses()->get();
      $assignees = User::select('id','name');
      $columns =
        array(
          '#' => '#',
          'id' => 'table_id',
          'code' => 'order-code',
          'organization_name' => 'organization-name',
          'user-name' => 'facility-user-name',
          'facility-name' => 'facility-name',
          'status_name' => 'status-name',
          'assignee_name' => 'assignee-name',
          'created_at' => 'order-created_at',
          'updated_at' => 'order-updated_at',
          'action' => 'action'
        );
      $assignees = $this->assignService->all_assignees_by_model(Order::class);
      $users_assignable = User::whereHas('roles', function ($query) {
        $query->whereIn('name', ['admin']);
      })->get();
  
  
      return view('admin.order_assigns.index', compact('statuses', 'organizations', 'columns','assignees','users_assignable'));
    
  }


  public function store(Request $request){
    $order = Order::findOrFail($request->order_id);
    $user_ids = $request->user_ids;
    $new_assigns_count = $this->assignService->assign_multiple_users_to_model($order,$user_ids);

    if($new_assigns_count > 0){
      return back()->with(['message'=>trans('translation.order-assigns-updated-successfully'),'alert-type' => 'success']);
    }
    return back()->with(['message'=>trans('translation.no-assigns-created'),'alert-type' => 'error']);
  }

  public function store_multiple(Request $request){
    $assignee = User::findOrFail($request->user_id);
    $order_ids = json_decode($request->order_ids, true);
    $new_aasignable_count = $this->assignService->assign_multiple_record_of_model_to_user($assignee, $order_ids, Order::class);

    if($new_aasignable_count > 0 ){
      SessionFlashService::setMessage(trans('translation.order-assigns-updated-successfully'));
      return back();//->with(['message'=>trans('translation.'),'alert-type' => 'success']);
      
    }
    SessionFlashService::setMessage(trans('translation.no-assigns-created'),'error');
    return back();//->with(['message'=>trans('translation.no-assigns-created'),'alert-type' => 'error']);
  }
  
  public function unassigns(Request $request){
    $assignee = User::findOrFail($request->user_id);
    $order_ids = json_decode($request->order_ids, true);
    $deleted_aasignable_count = $this->assignService->unassign_multiple_record_of_model_to_user($assignee, $order_ids, Order::class);
    
    if($deleted_aasignable_count > 0 ){
      SessionFlashService::setMessage(trans('translation.order-assigns-deleted-successfully'));
      return back();
      
    }
    SessionFlashService::setMessage(trans('translation.no-assigns-deleted'),'error');
    return back();
  }

  public function datatable(){
    $order_assigns_query = Order::with('assigns');

    $order_assigns_query->whereNotIn('status_id',[Status::CANCELED_ORDER,]);
    if (\request('organization_id')) {
      $order_assigns_query->whereHas('organization_service', function ($q) {

        $q->whereIn('organization_id', \request('organization_id'));
      });
    }

    if (\request('status_id')) {

      $order_assigns_query->whereIn('status_id', \request('status_id'));
    }

    if (\request('assignees_id')) {

      $order_assigns_query->whereHas('assignees',function($q){
        $q->whereIn('users.id',request('assignees_id'));
      });
    }

    $order_assigns = $order_assigns_query->orderByDesc('created_at')->get();
    return datatables($order_assigns)
            ->editColumn('code', function (Order $order) {
              $code = 'ORD' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
              return $code;
            })
            ->editColumn('facility-name', function (Order $order) { //  use ($is_chairman) {
              return  $order->facility->name;
            })
            ->addColumn('status_name', function (Order $order) {
              return "<span class='badge ' style='background:" . $order->status->color . "' >" . $order->status->name . "</span>";
            })
            ->editColumn('organization_name', function (Order $order) {
              return $order->organization_service->organization->name ?? '-';
            })
            ->editColumn('user-name', function (Order $order) {
              return $order->user->name ?? '-';
            })
            ->editColumn('created_at', function (Order $order) {
              if ($order->created_at != null) {
                return $order->created_at->toDateString() . ' - ' . $order->created_at->toTimeString();
              }
              return '';
            })
            ->editColumn('updated_at', function (Order $order) {
              if ($order->updated_at != null) {
                return $order->updated_at->toDateString() . ' - ' . $order->updated_at->toTimeString();
              }
              return '';
            })
            ->editColumn('assignee_name',function($row){
              if($row->assignees->isEmpty()){
                return trans('translation.no-assignee');
              }
              return $row->assignees
                ->map(fn($assignee) => '<span class="badge bg-primary">' . e($assignee->name) . '</span>')
                ->implode(' | ');
              // return $row->assignees->implode('name',',');
            })
            ->addColumn('action', function (Order $order) { //use ($is_chairman) {
              // <button
              //           class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 notes-button"
              //           data-bs-target="#notesModal"
              //           data-order-id="' . $order->id . '"
              //           data-bs-toggle="modal"
              //           data-original-title="Edit">
              //               <i class="mdi mdi-clipboard-edit-outline"></i>
              //         </button>
              $html =  // $is_chairman ? '' :
                '<a
                  class="btn btn-outline-secondary btn-sm m-1 on-default "
                  href="' . (route('orders.show', $order->id)) . '"
                  ><i class="mdi mdi-eye"></i>
                </a>';
              if ($order->status_id != Status::CANCELED_ORDER) {
                $html .=
                  '<button
                    class="btn btn-outline-info btn-sm m-1 on-default assign-order-button"
                    data-bs-target="#addassign-to-user"
                    data-order-id="' . $order->id . '"
                    data-bs-toggle="modal"
                    data-original-title="Assign"
                    ><i class="mdi mdi-account-multiple-plus-outline"></i>
                  </button>' .
                  '<a
                    class="btn btn-outline-primary btn-sm m-1 on-default "
                    href="' . (route('admin.orders.report', $order->uuid ?? fakeUuid())) . '"
                    target="_blank"
                    ><i class="mdi mdi-file-document-outline"></i>
                  </a>' .
                  ( //$is_chairman ? '' :
                    '<a target="_blank"
                    class="btn btn-outline-success btn-sm m-1 on-default "
                    href="' . (route('admin.orders.report', [$order->uuid ?? fakeUuid(), 'D'])) . '"
                    ><i class="mdi mdi-download-outline"></i>
                  </a>
                  ');
              } else {
                $html .= ''; //$is_chairman ? trans('translation.have-no-action') : '';
              }
              return $html;
            })
            ->rawColumns(['action','status_name','assignee_name'])
            ->toJson();

  }

}
