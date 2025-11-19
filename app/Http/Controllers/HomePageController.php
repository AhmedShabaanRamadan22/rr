<?php

namespace App\Http\Controllers;

use Throwable;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Period;
use App\Models\Sector;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\Monitor;
use App\Models\Support;
use App\Models\OrderSector;
use Illuminate\Http\Request;
use App\Models\OperationType;
use App\Models\SubmittedForm;
use App\Models\AttachmentLabel;
use App\Http\Requests\SectorRequest;
use App\Http\Resources\HomepageStatisticsResource;
use App\Http\Resources\TrackLocationResource;
use App\Models\TrackLocation;

class HomePageController extends Controller
{

    public function homepageStatistics(SectorRequest $sectorRequest)
    {
        // return response()->json(['message' => trans('translation.something went wrong')],400);

        $user = auth()->user()->id;
        $supports = Support::where([['order_sector_id', request()->order_sector_id], ['user_id', $user]])->get();
        $submitted_forms = SubmittedForm::where([['order_sector_id', request()->order_sector_id]]);
        $tickets = Ticket::where([['order_sector_id', request()->order_sector_id], ['user_id', $user]]);
        $last_action = TrackLocation::where('user_id', auth()->user()->id)->latest()->first();

        return response()->json([
            'supports' => $this->getStatistics($supports->count(), $supports->whereIn('status_id', [Status::CLOSED_SUPPORT, Status::HAS_ENOUGH_SUPPORT])->count()),
            'tickets' => $this->getStatistics($tickets->count(), $tickets->where('status_id', Status::CLOSED_TICKET)->count()),
            // 'submitted_forms' => $this->calculatePercentage($submitted_forms->count(), $submitted_forms->get()->where('is_completed', true)->count()),
            //TODO: 'meals' => $this->calculatePercentage($total, $part),
            'last_action' => $last_action? new TrackLocationResource($last_action) : null,
        ], 200);
    }


    public function getCurrentTime()
    {
        // Set the desired timezone
        $timezone = 'UTC+3';

        // Get the current time in the specified timezone
        $currentTime = Carbon::now()->addHour(3);
        return response()->json(['time' => $currentTime]);
    }

    protected function calculatePercentage($total, $part )
    {
        $percentage = $total == 0 ? "0.00" : number_format(($part / $total) * 100, 2);
        return $percentage;
    }

    protected function getStatistics($collection, $closed_status)
    {
        $data = [
            'total' => $collection,
            'closed' => $closed_status,
        ];
        $data['percentage'] = $this->calculatePercentage($data['total'], $data['closed']);
        return $data;
    }
}
