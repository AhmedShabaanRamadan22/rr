<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\CrudOperationTrait;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Str;

class NotificationController extends Controller
{
    use CrudOperationTrait;

  public function __construct()
  {
      $this->set_model($this::class);
  }

  public function dataTable(Request $request)
    {
        $query = $this->model::query();

        $query->forAuthUser();
        
        $query->orderByDesc('created_at');
        return datatables()->of($query)//->paginate(request()->input('per_page', 50)))
        ->editColumn('message', function ($row) {
            // dd($notification->data);
            // $data = is_array($notification->data) ? $notification->data : json_decode($notification->data, true);

            // return isset($data['message']) ? html_entity_decode($data['message']) : 'No Data';
            $assigner = $row->data['assigned_by'] ?? null;
            $assigner = isset($assigner) ? ', ' . trans('translation.by') . ' ' . $assigner : '';
            $message = $row->data['message'] ?? null;
            $message = isset($message) ? $message : trans('translation.no-data');

            $result = $message . $assigner;
            if($row->unread()){
                $result = Str::of($result)->start('<strong>')->finish('</strong>');
            }
                
            return $result;
        })
        ->editColumn('url', function ($row) {
            $url = $row->data['url'] ?? null;
            return isset($url) ? 
                        '<a href="' . route('readNotificationWithRedirect',$row->id) . '"> '. trans('translation.link') . '</a>': 
                        trans('translation.no-data');
        })
        ->editColumn('created_at', function ($row) {
            if ($row->created_at != null) {
                return $row->created_at .' (' . $row->created_at->diffForHumans() . ')';
            }
        })
        ->editColumn('read_at', function ($row) {
            if ($row->read_at != null) {
                return $row->read_at .' (' . $row->read_at->diffForHumans() . ')';
            }
            return '-';//trans('translation.not-read-yet');
        })
        ->addColumn('action', function ( $row) {
                return '<div class="d-flex justify-content-center">
                <button class="btn btn-outline-'.($row->unread() ?'secondary':'info').' btn-sm m-1  on-default m-r-5 switchRead" data-id="'. $row->id .'">
                    <i class="mdi mdi-email-' . ($row->unread() ? 'open-':'') . 'multiple"></i>
                </button>

                </div>';
            })
            ->rawColumns(['url','action'])
            ->toJson();
        }
        // <button
        // class="btn btn-outline-danger btn-sm m-1  on-default m-r-5 delete' . $this->table_name . '" data-model-id="' . $row->id . '">
        //     <i class="mdi mdi-delete"></i>
        // </button>
    
    public function checkRelatives($delete_model){
        // if($delete_model->{relation}->isNotEmpty()){
        //    return trans('translation.delete-{relation}-first');
        //}
        //return '';
    }

    public function readNotificationWithRedirect($notification_id)
    {
        $notification = Notification::find($notification_id);
        $notification->markAsRead();

        return redirect($notification->data['url']);

    }

    public function switchRead( $notification_id){
        $notification = Notification::find($notification_id);

        $notification->switchRead();

        return response(['message'=>trans('translation.Updated successfully')],200);
    }
}