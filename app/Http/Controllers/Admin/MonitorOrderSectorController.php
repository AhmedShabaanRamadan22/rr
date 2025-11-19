<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Monitor;
use App\Models\OrderSector;
use Illuminate\Http\Request;
use App\Notifications\CrudNotify;
use App\Models\MonitorOrderSector;
use App\Http\Controllers\Controller;

class MonitorOrderSectorController extends Controller
{

    public function store(Request $request)
    {
        //
        // dd($request->all());
        $monitor = Monitor::find($request->monitor_id);
        // MonitorOrderSector::create(['monitor_id' => $request->monitor_id, 'order_sector_id' => $request->order_sector]);
        foreach ($request->order_sector as $order_sector) {
            $new_mos = $monitor->monitor_order_sectors()->create(['order_sector_id' => $order_sector]);
            User::find($monitor->user->id)->notify(new CrudNotify($new_mos, 'create'));
        }
        return back()->with(array('message' => trans('translation.Added successfully'), 'alert-type' => 'success'));
    }
    //??=========================================================================================================
    public function destroy(string $id)
    {
        //
        $monitor = Monitor::find($id);
        foreach (request()->order_sector as $order_sector) {
            $del_mos = MonitorOrderSector::where(['monitor_id' => $id, 'order_sector_id' => $order_sector])->first();
            User::find($monitor->user->id)->notify(new CrudNotify($del_mos, 'delete'));
            $del_mos->delete();
        }
        return back()->with(array('message' => trans('translation.Deleted successfully'), 'alert-type' => 'success'));
    }
    //??=========================================================================================================
    public function move(Request $request)
    {
        //delete monitor
        $old_mos = MonitorOrderSector::where(['monitor_id' => $request->monitor_id, 'order_sector_id' => $request->move_from])->first();
        $from_sector = $old_mos->order_sector->sector->label;
        $old_mos->delete();
        //add monitor
        $monitor = Monitor::find($request->monitor_id);
        $new_mos = $monitor->monitor_order_sectors()->create(['order_sector_id' => $request->move_to]);
        $new_mos->from_sector = $from_sector;
        User::find($monitor->user->id)->notify(new CrudNotify($new_mos, 'update'));
        return back()->with(array('message' => trans('translation.moved successfully'), 'alert-type' => 'success'));
    }
    //??=========================================================================================================
    public function swap(Request $request)
    {
        //
        $monitor = Monitor::find($request->monitor_id);
        $from_order_sector = OrderSector::find($request->swap_from);

        $to_monitor_order_sector = MonitorOrderSector::find($request->swap_to);
        $second_monitor = $to_monitor_order_sector->monitor;
        $to_order_sector = $to_monitor_order_sector->order_sector;

        $existed_sector_in_second_monitor = $second_monitor->monitor_order_sectors->where('id', $from_order_sector->id);
        if (count($existed_sector_in_second_monitor) != 0) {
            return back()->with(array('message' => trans('translation.existed-sector'), 'alert-type' => 'error'));
        }

        $monitor->monitor_order_sectors()->create(['order_sector_id' => $to_order_sector->id]);
        MonitorOrderSector::where(['monitor_id' => $monitor->id, 'order_sector_id' => $from_order_sector->id])->delete();

        $second_monitor->monitor_order_sectors()->create(['order_sector_id' => $from_order_sector->id]);
        MonitorOrderSector::where(['monitor_id' => $second_monitor->id, 'order_sector_id' => $to_order_sector->id])->delete();

        return back()->with(array('message' => trans('translation.Added successfully'), 'alert-type' => 'success'));
    }
}