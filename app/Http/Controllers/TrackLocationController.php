<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use App\Models\Meal;
use App\Models\Assist;
use App\Models\Sector;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\Monitor;
use App\Models\Support;
use Illuminate\Http\Request;
use App\Models\TrackLocation;
use App\Services\ChartService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\LocationTrackerTrait;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\SectorTrackResource;
use App\Http\Resources\MonitorTrackResource;
use App\Http\Resources\TrackLocationResource;
use App\Http\Resources\TrackLocationStatResource;

class TrackLocationController extends Controller
{
    use LocationTrackerTrait;
    public function index()
    {
        $locations = TrackLocation::with('track_locationable')
            ->whereHas('track_locationable', function ($q) {
                $q->whereNull('deleted_at');
            })->whereDate('created_at', Carbon::today())
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['locations' => TrackLocationResource::collection($locations)], 200);
    }

    public function statistics()
    {
        $parameter_model = request()->model ?? null;
        if (!in_array($parameter_model, [null, "Order", 'Ticket', 'Support', 'Meal'])) {
            return response()->json(["message" => "wrong parameter"], 500);
        }

        $date = request()->date ?? Carbon::today();
        $organizations = ['2', '6', '7', '8', '5'];

        $locations = TrackLocation::with('track_locationable')
            ->whereHas('track_locationable', function ($q) {
                $q->whereNull('deleted_at');
            })
            ->whereDate('created_at', $date)
            ->orderByDesc('created_at')->get()->whereIn('organization_id', $organizations);

        $sectors = Sector::query()->whereHas('classification', function ($q) use ($organizations) {
            $q->whereIn('organization_id', $organizations);
        });

        $tickets = Ticket::with('reason_danger.danger', 'reason_danger.reason')->whereHas('order_sector.sector.classification', function ($query) use ($organizations) {
            $query->whereIn('organization_id', $organizations);
        });

        $supports = Support::query()->whereHas('order_sector.sector.classification', function ($query) use ($organizations) {
            $query->whereIn('organization_id', $organizations);
        });

        $assists_received = Assist::query()->whereHas('support.order_sector.sector.classification', function ($query) use ($organizations) {
            $query->whereIn('organization_id', $organizations);
        });

        $assists_sent = Assist::query()->whereHas('assist_sent.classification', function ($query) use ($organizations) {
            $query->whereIn('organization_id', $organizations);
        });

        $meals = Meal::query()->whereHas('sector.classification', function ($query) use ($organizations) {
            $query->whereIn('organization_id', $organizations);
        });

        $monitors = Monitor::query()->whereDoesntHave('user.roles', function ($q) {
            $q->whereIn('name', ['supervisor', 'boss']);
        })
            ->whereHas('monitor_order_sectors', function ($query) use ($organizations) {
                $query->whereHas('order_sector.sector.classification', function ($query) use ($organizations) {
                    $query->whereIn('organization_id', $organizations);
                });
            });

        if (\request('date')) {
            $locations = $locations->filter(function ($location) {
                return $location->created_at->toDateString() === \request('date');
            });
            $tickets = $tickets->whereDate('created_at', \request('date'));
            $supports = $supports->whereDate('created_at', \request('date'));
            $assists_received = $assists_received->whereDate('created_at', \request('date'));
            $assists_sent = $assists_sent->whereDate('created_at', \request('date'));
            $meals = $meals->whereDate('day_date', \request('date'));
        } else {
            $locations = $locations->filter(function ($location) {
                return $location->created_at->toDateString() === Carbon::today()->toDateString();
            });
        }

        if (\request('organization_id')) {
            $locations = $locations->where('organization_id', \request('organization_id'));
            $sectors = $sectors->whereHas('classification', function ($q) {
                $q->where('organization_id', \request('organization_id'));
            });
            $tickets = $tickets->whereHas('order_sector.sector.classification', function ($query) {
                $query->where('organization_id', \request('organization_id'));
            });
            $supports = $supports->whereHas('order_sector.sector.classification', function ($query) {
                $query->where('organization_id', \request('organization_id'));
            });
            $assists_received = $assists_received->whereHas('support.order_sector.sector.classification', function ($query) {
                $query->where('organization_id', \request('organization_id'));
            });
            $assists_sent = $assists_sent->whereHas('assist_sent.classification', function ($query) {
                $query->where('organization_id', \request('organization_id'));
            });
            $meals = $meals->whereHas('sector.classification', function ($query) {
                $query->where('organization_id', \request('organization_id'));
            });
            $monitors = $monitors
                ->whereHas('monitor_order_sectors', function ($query) {
                    $query->whereHas('order_sector.sector.classification', function ($query) {
                        $query->where('organization_id', \request('organization_id'));
                    });
                });
        }

        if (\request('order_sector_id')) {
            $locations = $locations->where('order_sector_id', \request('order_sector_id'));
            $sectors = $sectors->whereHas('order_sectors', function ($q) {
                $q->where('id', \request('order_sector_id'));
            });
            $tickets = $tickets->where('order_sector_id', \request('order_sector_id'));
            $supports = $supports->where('order_sector_id', \request('order_sector_id'));
            $assists_received = $assists_received->whereHas('support.order_sector', function ($query) {
                $query->where('id', \request('order_sector_id'));
            });
            $assists_sent = $assists_sent->whereHas('assist_sent.order_sectors', function ($query) {
                $query->where('id', \request('order_sector_id'));
            });
            $meals = $meals->whereHas('sector.order_sectors', function ($query) {
                $query->where('id', \request('order_sector_id'));
            });
            $monitors = $monitors->whereHas('monitor_order_sectors', function ($query) {
                $query->whereHas('order_sector', function ($query) {
                    $query->where('order_sector_id', \request('order_sector_id'));
                });
            });
        }

        if (\request('sector_id')) {
            $locations = $locations->filter(function ($q) {
                return $q->track_locationable->order_sector_obj()?->sector_id == \request('sector_id');
            });
            $sectors = $sectors->where('id', \request('sector_id'));
            $tickets = $tickets->whereHas('order_sector', function ($query) {
                $query->where('sector_id', \request('sector_id'));
            });
            $supports = $supports->whereHas('order_sector', function ($query) {
                $query->where('sector_id', \request('sector_id'));
            });
            $assists_received = $assists_received->whereHas('support.order_sector', function ($query) {
                $query->where('sector_id', \request('sector_id'));
            });
            $assists_sent = $assists_sent->where('assist_sector_id', \request('sector_id'));
            $meals = $meals->where('sector_id', \request('sector_id'));
            $monitors = $monitors->whereHas('monitor_order_sectors', function ($query) {
                $query->whereHas('order_sector.sector', function ($query) {
                    $query->where('id', \request('sector_id'));
                });
            });
        }

        if (\request('monitor_id')) {
            $user_id = Monitor::find(\request('monitor_id'))?->user->id;
            $locations = $locations->where('user_id', $user_id);
            $sectors = $sectors->whereHas('order_sectors', function ($q) {
                $q->whereHas('monitor_order_sectors', function ($q) {
                    $q->where('monitor_id', \request('monitor_id'));
                });
            });
            $tickets = $tickets->where('user_id', $user_id);
            $supports = $supports->where('user_id', $user_id);
            $assists_received = $assists_received->where('assistant_id', $user_id);
            $assists_sent = $assists_sent->where('assistant_id', $user_id);
            $meals = $meals->whereHas('sector', function ($q) use ($user_id) {
                $q->whereHas('order_sectors', function ($q) {
                    $q->whereHas('monitor_order_sectors', function ($q) {
                        $q->where('monitor_id', \request('monitor_id'));
                    });
                });
            });
            $monitors = $monitors->where('id', 'monitor_id');
        }

        $sectors_collection = $sectors->with([
            'classification.organization.organization_services',
            'nationality_organization.nationality',
            'supervisor',
            'order_sectors.order.organization_service',
            'boss',
            'order_sectors.order.facility',
        ])->get();
        $tickets_collection = $tickets->with([
            'reason_danger.danger',
            'reason_danger.reason',
            'status',
        ])->get();
        $supports_collection = $supports->with([
            'assists',
            'status',
            'reason_danger.danger',
            'reason_danger.reason',
        ])->get();
        $assists_received_collection = $assists_received->with([
            'support',
            'status',
        ])->get();
        $assists_sent_collection = $assists_sent->with([
            'support',
            'status',
        ])->get();
        $meals_collection = $meals->with([
            'status',
            'meal_organization_stages.organization_stage.stage_bank',
        ])->orderBy('status_id')->get();
        $monitors_collection = $monitors->with([
            'user',
            'monitor_order_sectors.order_sector.sector.classification.organization',
            ])->get();

        $data = [
            'locations' => TrackLocationStatResource::collection($locations),
            'sectors' => $sectors_collection->count(),
            'sectors_table' => SectorTrackResource::collection($sectors_collection),
            'pilgrams' => $sectors->sum('guest_quantity'),
            'pilgrams_nationalities' => $this->nationalitiesCount($sectors),
            'monitors' => $monitors->count(),
            'monitors_table' => MonitorTrackResource::collection($monitors_collection),
        ];

        if ($parameter_model == "Order" || $parameter_model == null) {
            $data += [];
        }
        if ($parameter_model == "Ticket" || $parameter_model == null) {
            $data += [
                'tickets' => $tickets->count(),
                'tickets_by_danger' => $this->countByAttribute($tickets_collection, 'reason_danger.danger.level'),
                'tickets_by_reason' => $this->countByAttribute($tickets_collection, 'reason_danger.reason.name'),
                'tickets_by_status' => $this->countByAttribute($tickets_collection, 'status.name_ar'),
            ];
        }

        if ($parameter_model == "Support" || $parameter_model == null) {
            $data += [
                'supports_total' => $supports->count(),
                'supports_water_by_day' => $this->getModelGroupedByDate($supports_collection->where('type', '3')),
                'supports_food_by_day' => $this->getModelGroupedByDate($supports_collection->where('type', '2')),
                'supports_water_by_day_quantity' => $this->getModelGroupedByDateAndSumAttribute($supports_collection->where('type', '3'), 'delivered_quantity'),
                'supports_food_by_day_quantity' => $this->getModelGroupedByDateAndSumAttribute($supports_collection->where('type', '2'), 'delivered_quantity'),

                'assists_received' => $assists_received->count(),
                'assists_received_water_by_status' => $this->countByAttribute($assists_received_collection->where('support_type', '3'), 'status.name_ar'),
                'assists_received_food_by_status' => $this->countByAttribute($assists_received_collection->where('support_type', '2'), 'status.name_ar'),
                'assists_sent' => $assists_sent->count(),
                'assists_sent_water_by_status' => $this->countByAttribute($assists_sent_collection->where('support_type', '3'), 'status.name_ar'),
                'assists_sent_food_by_status' => $this->countByAttribute($assists_sent_collection->where('support_type', '2'), 'status.name_ar'),
            ];
        }

        if ($parameter_model == "Meal" || $parameter_model == null) {
            $data += [
                'meals' => $meals->count(),
                'meals_by_status' => $this->countByAttribute($meals_collection, 'status.name_ar'),
                'meals_by_stage' => $this->countByAttribute($meals_collection, 'current_meal_organization_stage.organization_stage.stage_bank.name'),
                'meals_by_day' => $this->getModelGroupedByDate($meals_collection->where('status_id', Status::DONE_MEAL)),
            ];
        }

        return response()->json($data, 200);
       
    }

    public function show($track_location_id)
    {
        $trackLocation = TrackLocation::findOrFail($track_location_id);
        return response()->json(['location' => new TrackLocationResource($trackLocation)], 200);
    }

    public function store(Request $request)
    {
        // $location_track = $this->tracker($request, 'testing from location track controller', $this);
        // $jsonData = json_decode($request->device_info, true);
        // // dd($jsonData['appLanguage'],$jsonData['model']);
        // $location_track = TrackLocation::create(
        //     $request->only(['details', 'davice', 'latitude', 'longitude']) +
        //         ['device_info' => $jsonData] +
        //         ['user_id' => Auth::user()->id]
        // );
        // return response()->json(['location_track' => $location_track], 200);
        return response()->json(['message' => 'not working right now'], 400);
    }
    
    public static function getModelGroupedByDate($model)
    {
        $dateCategories = dates_range(7, 0, 'm-d-Y');
        $model_data = $model->CountBy(function ($date) {
            if (get_class($date) == "App\Models\Meal") { //bc the meals depeneds on day_date not create_at attribute
                return Carbon::parse($date->day_date)->format('m-d-Y');
            }
            return Carbon::parse($date->created_at)->format('m-d-Y');
        });
        $data = collect($dateCategories)
            ->map(function ($value, $key) use ($model_data) {
                return [
                    'date' => $value,
                    'count' => $model_data->get($value) ?? 0,
                ];
            })->values();

        return $data;
    }

    public function nationalitiesCount($sectors)
    {
        return $sectors->with(['nationality_organization.nationality:id,name,flag'])
            ->get()
            ->groupBy('nationality_organization.nationality.name')
            ->map(function ($items, $nationalityName) {
                $flag = $items->first()->nationality_organization->nationality->icon ?? '';
                return [
                    'nationality_name' => $nationalityName,
                    'total_guests' => $items->sum('guest_quantity'),
                    'flag_icon' => $flag
                ];
            })
            ->values();
    }

    public function countByAttribute($collection, $attribute)
    {
        return $collection->groupBy($attribute)
            ->map(function ($items, $value) {
                return [
                    'attribute' => $value,
                    'count' => $items->count()
                ];
            })
            ->values();
    }

    public static function getModelGroupedByDateAndSumAttribute($model, $sum_attribute)
    {
        $data = [];

        $dateCounts = dates_range(7, 0, 'm-d-Y');
        $dateCounts = array_combine(array_values($dateCounts), array_fill(0, count($dateCounts), null));

        foreach ($model as $item) {
            $dateValue = Carbon::parse($item->created_at)->format('m-d-Y');
            if (in_array($dateValue, array_keys($dateCounts))) {
                $dateCounts[$dateValue] += $item->{$sum_attribute};
            }
        }

        foreach ($dateCounts as $date => $sum) {
            $data[] = [
                'date' => $date,
                'sum' => $sum ?? 0
            ];
        }

        return $data;
    }
}
