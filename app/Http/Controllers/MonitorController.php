<?php

namespace App\Http\Controllers;

use App\Models\Monitor;
use Illuminate\Http\Request;
use App\Http\Resources\MonitorTrackResource;

class MonitorController extends Controller
{
    public function index(){
        $organizations = ['1','2'];
        $monitors = Monitor::whereHas('monitor_order_sectors', function ($query) use ($organizations) {
            $query->whereHas('order_sector.sector.classification', function ($query) use ($organizations) {
                $query->whereIn('organization_id', $organizations);
            });
        })->get();

        return response()->json(['monitors' => MonitorTrackResource::collection($monitors)],200);
    }
}
