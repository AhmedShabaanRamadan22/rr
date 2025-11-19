<?php

namespace App\Http\Controllers\Admin;

use App\Models\Fine;
use App\Models\User;
use App\Models\Status;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Notifications\CrudNotify;
use App\Models\MonitorOrderSector;
use App\Traits\CrudOperationTrait;
use App\Http\Controllers\Controller;

class FineController extends Controller
{
    use CrudOperationTrait;

    public function __construct()
    {
        $this->set_model($this::class);
    }
    //??================================================================
    public function show(Fine $fine)
    {
        return view('admin.fines.show', compact('fine'));
    }
    //??================================================================
    public function dataTable(Request $request)
    {
        $query = Fine::select(
            ['id','fine_organization_id', 'user_id', 'order_sector_id', 'status_id']
        )->with(
            'user:id,name',
            'status:id,name_ar,name_en,color',
            'order_sector.sector:id,label',
            'order_sector.order:id',
            'fine_organization.fine_bank:id,name',
            'fine_organization.organization:id,name_en,name_ar,slug',
        );
        // $statuses = Status::where('type', 'fines')->get();
        $closed_status = Status::REJECTED_FINE;
        if (\request('organization_id')) {
            $query->whereHas('fine_organization.organization', function ($q1) {
                $q1->whereIn('id', \request('organization_id'));
            });
        }
        if (\request('sector_id')) {
            $query->whereHas('order_sector', function ($q1) {
                $q1->whereIn('sector_id', \request('sector_id'));
            });
        }
        if (\request('user_id')) {
            $query->whereHas('user', function ($q1) {
                $q1->whereIn('id', \request('user_id'));
            });
        }
        if (\request('fine_id')) {
            $query->whereHas('fine_organization.fine_bank', function ($q1) {
                $q1->whereIn('id', \request('fine_id'));
            });
        }
        return datatables($query->orderByDesc('created_at')->get())
            ->editColumn('fine_name', function ($fine) {
                return $fine->fine_organization->fine_bank->name ?? '';
            })
            ->editColumn('user_name', function ($fine) {
                return $fine->user->name ?? '';
            })
            ->editColumn('order_sector', function ($fine) {
                $order_sector_name = $fine->order_sector->sector->label ?? '' ;
                // $order_sector_name .= ' - ' . $fine->order_sector->order->organization_service->service->name?? '';
                return $order_sector_name;
            })
            ->editColumn('code', function (Fine $fine) {
                $code = $fine->fine_organization->organization->slug .
                        '-FN' . str_pad($fine->id, 4, '0', STR_PAD_LEFT) . '-' . 'OR'.
                        str_pad($fine->order_sector->order->id, 3, '0', STR_PAD_LEFT) ;
                return $code;
            })
            ->editColumn('status_id', function (Fine $fine) use ( $closed_status) {
                $disabled = ($fine->status_id == $closed_status); //? (auth()->user()->canChangefineStatus() ? '' : 'disabled ') : '';
                return "<span class='badge ' style='background:" . $fine->status->color . "'  $disabled >" . $fine->status->name . "</span>";
                // $html = '<div><select class="form-control selectpicker status-select"' . $disabled . 'name="service_id" style="background:' . $fine->status->color . '" data-status-id="' . $fine->status_id . '" data-fine-id="' . $fine->id . '" onchange="changeFineSelectPicker(this)">';
                // foreach ($statuses as $status) {
                //     $span = " data-content=\"<span class='badge ' style='background:" . $status->color . "' >" . $status->name . "</span>\" ";
                //     $html .= '<option value="' . $status->id . '" ' . ($status->id == $fine->status->id ? 'selected' : '') . ' ' . $span . ' >' . $status->name . '</option>';
                // }
                // $html .= "</select></div>";
                // return $html;
            })
            ->editColumn('organization_name', function ($fine) {
                return $fine->fine_organization->organization->name ?? '';
            })
            ->addColumn('more_details', function ($row) {
                return '<div class="d-flex justify-content-center">
                <a class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5" href="' . (route('fines.show', $row->id)) . '" ><i class="mdi mdi-eye"></i></a>
                
            </div>';
            })
            ->rawColumns(['fine_name', 'status_id', 'user_name', 'order_sector', 'organization_name', 'more_details'])
            ->toJson();
    }
    //??================================================================
    public function update_status(Request $request)
    {
        $fine = Fine::find($request->fine_id);
        $fine->update(['status_id' => $request->status_id]);
        User::find($fine->user->id)->notify(new CrudNotify($fine, 'changeStatus'));
        return response(['message' => trans('translation.Updated successfuly')], 200);
    }
}