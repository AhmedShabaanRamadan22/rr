<?php

namespace App\Http\Controllers\Admin;

use PDO;
use App\Models\Order;
use App\Models\Sector;
use App\Models\OrderSector;
use Illuminate\Http\Request;
use App\Models\MonitorOrderSector;
use App\Traits\CrudOperationTrait;
use App\Http\Controllers\Controller;
use App\Models\Status;

use function PHPUnit\Framework\isNull;

class OrderSectorController extends Controller
{
    use CrudOperationTrait;

    public function __construct()
    {
        $this->set_model($this::class);
    }

    public function store(Request $request)
    {
        $sector = Sector::find($request->sector_id);
        $order = Order::find($request->order_id);
        $service = $order->organization_service;
        $active_order_sector = $sector->active_order_sector_service($service->id);

        $new_order_sector = OrderSector::create([
            "order_id" => $request->order_id,
            "sector_id" => $request->sector_id,
            "parent_id" => $active_order_sector->first()->id ?? null,
        ]);

        if (!is_null($new_order_sector->parent_id)) {
            $archived_order_sector = OrderSector::withTrashed()->withArchived()->where('sector_id', $request->sector_id)->whereHas('order.organization_service', function ($q) use ($service) {
                $q->where('id', $service->id);
            })->latest()->first();
            
            if(!is_null($archived_order_sector) && $archived_order_sector != $new_order_sector){
                foreach ($archived_order_sector->meals->where('status_id', '!=', Status::DONE_MEAL) as $meal) {
                    $meal->update('order_sector_id', $new_order_sector->id);
                }
            }
        }

        return back()->with(array('message' => trans('translation.Added successfully'), 'alert-type' => 'success'));
    }
    public function setActive($order_sector_id)
    {
        //archive all the order sectors (parent with their children)
        //then create a new order sectors with the new parent and the children referencing them
        $order_sector = OrderSector::findOrFail($order_sector_id);
        if ($order_sector->is_active) {
            return back()->with(array('message' => trans('translation.already-active'), 'alert-type' => 'error'));
        }
        $previous_parent = $order_sector->parent()->first();
        $old_moses = $previous_parent->monitor_order_sectors;
        $children = $previous_parent->children();
        $meals = $previous_parent->meals->where('status_id', '!=', Status::DONE_MEAL);

        $new_parent_os  = OrderSector::create($order_sector->toArray()); //new parent
        
        //place it bafore the archiving the previous parent or it cause an err
        //creating new mos 
        foreach ($old_moses as $old_mos) {
            // $new_mos = MonitorOrderSector::create($old_mos->toArray());
            $old_mos->update(['order_sector_id' => $new_parent_os->id]);
            // $old_mos->archive();
        }
        foreach ($meals as $meal) {
            $meal->update(['order_sector_id' => $new_parent_os->id]);
        }
        
        $new_parent_os->update(['parent_id' => null]); //update parent id 
        $order_sector->archive(); //archive old os
        // $order_sector->update(['parent_id' => null]);

        foreach ($children as $child) {
            $new_child = OrderSector::create($child->toArray()); //new child os 
            $new_child->update(['parent_id' => $new_parent_os->id]); //reference new parent
            $child->archive(); //archive old child os
            // $child->update(['parent_id' => $order_sector->id]);
        }

        $prev_parent_os = OrderSector::create($previous_parent->toArray()); //new child os that was a parent
        $prev_parent_os->update(['parent_id' => $new_parent_os->id]); //update parent id 
        $previous_parent->archive(); //archive old parent os

        return back()->with(array('message' => trans('translation.updated successfully'), 'alert-type' => 'success'));
    }
    public function destroy(String $id)
    {
        $order_sector = OrderSector::findOrFail($id);
        // dd($order_sector->has_operations, $order_sector->children->isNotEmpty(), $order_sector->is_active);
        if($order_sector->has_operations){
            if($order_sector->children->isNotEmpty()){
                //archive all children
                foreach($order_sector->children as $child){
                    $child->archive();
                }
            }
            //archive the mos then archive the parent
            if($order_sector->monitor_order_sectors){
                foreach($order_sector->monitor_order_sectors as $mos){
                    $mos->archive();
                }
            }
            $order_sector->archive();

        }elseif($order_sector->is_active){//if parent but no operations 
            if($order_sector->children->isNotEmpty()){
                //assigning new parent from children 
                //make mos refernce the new parent
                //archive the old parent
                $candidate_active = $order_sector->children->sortBy('created_at')->first();
                foreach($order_sector->children as $child){
                    $child->update(['parent_id' => $candidate_active->id]);
                }
                if($order_sector->monitor_order_sectors){
                    foreach($order_sector->monitor_order_sectors as $mos){
                        $mos->update(['order_sector_id' => $candidate_active->id]);
                    }
                }
                $candidate_active->update(['parent_id' => null]);
                $order_sector->delete();
            }else{//if parent with no children
                if($order_sector->monitor_order_sectors){
                    foreach($order_sector->monitor_order_sectors as $mos){
                        $mos->delete();
                    }
                }
                $order_sector->delete();
            }
        }else{//if not active then its child 
            $order_sector->delete();
        }
        return response(['message' => trans('translation.deleted successfully')], 200);
    }
    public function dataTable(Request $request)
    {
        $query = $this->model::with(
            'sector:id,label,sight,boss_id,supervisor_id',
            'children.order.facility:id,name',
            'order.facility:id,name',
            'sector.supervisor:id,name',
            'sector.boss:id,name',
            'monitor_order_sectors.monitor.user:id,name',
            'order.organization_service.organization:id,name_ar,name_en',
            'order.organization_service.service:id,name_ar,name_en',
        )->whereNull('parent_id')->orderByDesc('created_at')->get();
        return datatables($query)
            ->editColumn('name', function ($row) {
                return $row->order->facility->name;
            })
            ->editColumn('child_names', function ($row) {
                return $row->child_names;
                // return $row->child_names == "" ? trans('translation.order-sector-has-no-children') : $row->child_names;
            })
            ->editColumn('sector_label', function ($row) {
                return $row->sector->label;
            })
            ->editColumn('sight', function ($row) {
                return $row->sector->sight;
            })
            ->editColumn('service', function ($row) {
                return $row->order->organization_service->service->name;
            })
            ->editColumn('organization', function ($row) {
                return $row->order->organization_service->organization->name;
            })
            ->editColumn('boss', function ($row) {
                return $row->sector->boss->name ?? "-";
            })
            ->editColumn('supervisor', function ($row) {
                return $row->sector->supervisor->name ?? "-";
            })
            ->editColumn('monitors', function ($row) {
                return count($row->monitors_name) == 0 ? trans('translation.no-data') : $row->monitors_name; 
            })
            ->rawColumns(['is_required', 'action'])
            ->toJson();
    }
}