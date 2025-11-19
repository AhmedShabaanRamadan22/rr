<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Sector;
use Illuminate\Http\Request;
use App\Traits\CrudOperationTrait;
use App\Http\Controllers\Controller;
use App\Models\AttachmentLabel;

class SectorController extends Controller
{
    use CrudOperationTrait;

    public function __construct()
    {
        $this->set_model($this::class);
    }
    public function dataTable(Request $request)
    {

        $query = $this->model::with(
            // 'classification',
            'nationality_organization.nationality:id,name',
            'supervisor:id,name,phone',
            'boss:id,name,phone',
            'classification.organization:id,name_ar,name_en',
            'order_sectors.monitor_order_sectors.monitor.user:id,name,phone',
            'order_sectors.order.facility:id,name,user_id',
    );
        // dd(\request('organization_id'));
        if (\request('organization_id')) {
            $query->whereHas('classification', function ($q) {
                $q->whereIn('organization_id', \request('organization_id'));
            });
        }

        if (\request('classification_id')) {
            $query->whereIn('classification_id',\request('classification_id'));
        }

        if (\request('facility_id')) {
            $query->ActiveOrderSectorByServiceId(1,\request('facility_id'));

        }

        if (\request('nationality_id')) {
            $query->whereHas('nationality_organization', function ($q) {
                $q->whereIn('nationality_id', \request('nationality_id'));
            });
        }
        
        if (\request('boss_id')) {
            $query->whereIn('boss_id',\request('boss_id'));
        }
        
        if (\request('supervisor_id')) {
            $query->whereIn('supervisor_id',\request('supervisor_id'));
        }
        
        if (\request('monitor_id')) {
            $query->whereHas('order_sectors', function ($q) {
                $q->whereHas('monitor_order_sectors', function ($q) {
                    $q->whereHas('monitor', function ($q) {
                        $q->whereIn('user_id', \request('monitor_id'));
                    });
                });
            });
        }

        // dd($query->get());
        return datatables($query->orderByDesc('created_at')->get())
            ->editColumn('organization.name', function ($row) {
                return $row->classification->organization->name;
            })
            ->editColumn('boss_name', function ($row) {
                return ($row->boss->name??"-") .' <br>'. ($row->boss->phone??"-");
            })
            ->editColumn('supervisor_name', function ($row) {
                return ($row->supervisor->name??"-") .' <br>'. ($row->supervisor->phone??"-");
            })
            ->editColumn('arafah_location', function ($row) {
                return $row->arafah_location ?? '-';
            })
            ->editColumn('order_code', function ($row) {
                $order = $row->active_order_sector_organization_service(1)?->first()?->order;
                if($order){
                    return ($order->code??'-');
                }
                return '('. trans('translation.no-order-sector-yet') .')';
            })
            ->editColumn('provider', function ($row) {
                $provider = $row->active_order_sector_organization_service(1)?->first()?->order?->facility;
                if($provider){
                    return ($provider->name??'-') . ' | ' . ($provider->user->phone??'-');
                }
                return '('. trans('translation.no-order-sector-yet') .')';
            })
            ->editColumn('monitors', function ($row) {
                $monitors_name_array =  $row->monitors_name_with_phone_array;
                if( $monitors_name_array == null){
                    return  trans('translation.no-monitor-assigned-yet');
                }
                $html = '';
                $last_item = end($monitors_name_array);
                foreach($monitors_name_array as $index => $monitor_name){
                    $html .= '<span class="badge bg-primary mx-1 mb-2">' . $monitor_name . '</span>' . ( $last_item == $monitor_name ? '' : ' | ' );
                    if ( ($index+1) % 3 == 0 ) {
                        $html .= '<br>';
                    }
                }
                return $html;
            })
            ->addColumn('action', function ($row) {
                $map = $row->location ? '<a href="'.$row->location.'" class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 " target="_blank">
                <i class="mdi mdi-map"></i>
            </a>' : '';
                return '<div class="d-flex justify-content-center">
                <a href="'.route((str_replace('_','-',$this->table_name)).'.edit',$row->id).'" class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 ">
                    <i class="mdi mdi-square-edit-outline"></i>
                </a>'
                . $map .

              '<button
              class="btn btn-outline-danger btn-sm m-1  on-default m-r-5 deleteSector deletesectors" data-model-id="' . $row->id . '">
                  <i class="mdi mdi-delete"></i>
              </button>
          </div>';
            })
            ->rawColumns(['color', 'action', 'monitors','boss_name','supervisor_name','provider'])
            ->toJson();
    }

    public function getColumnOptionParameters($modelItem)
    {
        return $modelItem->classification->organization ?? null;
    }

    public function getAttachmentLabels($modelItem)
    {
        return AttachmentLabel::find(AttachmentLabel::SECTOR_SIGHT_LABEL);
    }

    public function checkRelatives($delete_model){
        if(
            $delete_model->order_sectors->isNotEmpty() ||
            // $delete_model->supports->isNotEmpty() ||
            // $delete_model->contracts->isNotEmpty() ||
            // $delete_model->tickets->isNotEmpty() ||
            $delete_model->meals->isNotEmpty()
            ){
            return trans('translation.delete-relative-first');
        }
        return '';
    }

    public function destroy(string $id)
    {
        $delete_model = $this->model::findOrFail($id);
        if (method_exists($this, 'checkRelatives')) {
            if (($message =  $this->checkRelatives($delete_model)) != '') {
                // return response(array('message' => $message, 'alert-type' => 'error'), 400);
                return response()->json(['message' => $message], 400);
            }
        }
        $delete_model->delete();
        return response()->json(['message' =>  trans('translation.delete-successfully')], 200);
        // return response(array('message' => trans("translation.Deleted successfully"), 'alert-type' => 'success'), 200);
    }
}
