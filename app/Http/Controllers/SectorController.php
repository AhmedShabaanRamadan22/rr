<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Sector;
use App\Models\Ticket;
use App\Models\Monitor;

use App\Models\Support;
use Illuminate\Http\Request;
use App\Models\SubmittedForm;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\SectorResource;
use App\Http\Resources\MonitorResource;
use App\Http\Resources\SupportResource;
use function PHPUnit\Framework\isEmpty;
use App\Http\Resources\OrderSectorResource;
use App\Http\Resources\SectorTrackResource;
use App\Http\Resources\SubmittedFormResource;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Resources\TicketResource as ResourcesTicketResource;
use Illuminate\Support\Facades\Event;

class SectorController extends Controller
{
    public function userSectors()
    {
        try {
            $user = Auth::user()
                ->load([
                    'roles',
                    'boss_sectors' => [
                        'classification.organization.organization_services',
                        'order_sectors' => [
                            'order' => [
                                'facility',
                                'organization_service' => [
                                    'organization',
                                    'service'
                                ],
                            ],
                            'monitor_order_sectors.monitor.user' => [
                                'country',
                                'profile_photo_attachment',
                                'bravo',
                            ],
                            'sector' => [
                                'nationality_organization.nationality',
                                'order_sectors' => [
                                    'order' => [
                                        'facility',
                                        'organization_service'
                                    ],
                                    'monitor_order_sectors.monitor.user' => [
                                        'country',
                                        'profile_photo_attachment',
                                        'bravo',
                                    ],
                                ],
                                'classification.organization' => [
                                    'organization_services',
                                    'logo_attachment'
                                ],
                                'boss' => [
                                    'country',
                                    'profile_photo_attachment'
                                ],
                                'supervisor' => [
                                    'country',
                                    'profile_photo_attachment'
                                ],
                                'attachment',
                            ],
                            'children.order.facility',
                            'parent'
                        ]
                    ],
                    'supervisor_sectors' => [
                        'classification.organization.organization_services',
                        'order_sectors' => [
                            'order' => [
                                'facility',
                                'organization_service' => [
                                    'organization',
                                    'service'
                                ],
                            ],
                            'monitor_order_sectors.monitor.user' => [
                                'country',
                                'profile_photo_attachment',
                                'bravo',
                            ],
                            'sector' => [
                                'nationality_organization.nationality',
                                'order_sectors' => [
                                    'order' => [
                                        'facility',
                                        'organization_service'
                                    ],
                                    'monitor_order_sectors.monitor.user' => [
                                        'country',
                                        'profile_photo_attachment',
                                        'bravo',
                                    ],
                                ],
                                'classification.organization' => [
                                    'organization_services',
                                    'logo_attachment'
                                ],
                                'boss' => [
                                    'country',
                                    'profile_photo_attachment'
                                ],
                                'supervisor' => [
                                    'country',
                                    'profile_photo_attachment'
                                ],
                                'attachment',
                            ],
                            'children.order.facility',
                            'parent'
                        ]
                    ],
                ]);
            $monitor = Monitor::where('user_id', $user->id)
                ->with([
                    'user' => [
                        'country',
                        'profile_photo_attachment',
                        'bravo',
                    ],
                    'monitor_order_sectors.order_sector' => [
                            'order' => [
                                'facility',
                                'organization_service' => [
                                    'organization',
                                    'service'
                                ],
                            ],
                            'monitor_order_sectors.monitor.user' => [
                                'country',
                                'profile_photo_attachment',
                                'bravo',
                            ],
                            'sector' => [
                                'nationality_organization.nationality',
                                'order_sectors' => [
                                    'order' => [
                                        'facility',
                                        'organization_service'
                                    ],
                                    'monitor_order_sectors.monitor.user' => [
                                        'country',
                                        'profile_photo_attachment',
                                        'bravo',
                                    ],
                                ],
                                'classification.organization' => [
                                    'organization_services',
                                    'logo_attachment'
                                ],
                                'boss' => [
                                    'country',
                                    'profile_photo_attachment'
                                ],
                                'supervisor' => [
                                    'country',
                                    'profile_photo_attachment'
                                ],
                                'attachment',
                            ],
                            'children.order.facility',
                            'parent'
                        ]
                ])
                ->first();
            $all_monitor_sectors = $monitor?->monitor_order_sectors;
            $order_sectors = collect();
            if ($user->hasRole('boss') && $user->boss_sectors) {
                $order_sectors = $this->getOrderSectors($user->boss_sectors, $order_sectors);
            }
            if ($user->hasRole('supervisor') && $user->supervisor_sectors) {
                $order_sectors = $this->getOrderSectors($user->supervisor_sectors, $order_sectors);
            }
            if ($all_monitor_sectors) {
                foreach ($all_monitor_sectors as $monitor_sector) {
                    $order_sector = $monitor_sector->order_sector;
                    $order_sectors->push($order_sector);
                }
            }

            $order_sectors = $order_sectors->unique('id')->sortBy(function ($orderSector) {
                return $orderSector->sector->label; // Adjust this based on your actual structure
            });
            $order_sectors = new \Illuminate\Pagination\LengthAwarePaginator(
                $order_sectors->forPage(\Illuminate\Pagination\Paginator::resolveCurrentPage(), \request('per_page') ?? 5),
                $order_sectors->count(),
                \request('per_page') ?? 5
            );
            return response()->json([
                'monitor' => new MonitorResource($monitor),
                'sectors' => OrderSectorResource::collection($order_sectors),
                'pages' => $order_sectors->lastPage(),
            ], 200);
        } catch (Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        // $organizations = ['1','2'];
        $organizations = ['2', '6', '7', '8', '5']; //,'3'];
        $sectors = Sector::whereHas('classification.organization', function ($q) use ($organizations) {
            $q->whereIn('id', $organizations);
        })->get();
        return response()->json(['sectors' => SectorTrackResource::collection($sectors)], 200);
    }

    public function show($sector_id)
    {
        $sector = Sector::findOrFail($sector_id);
        return response()->json(['sector' => new SectorResource($sector)], 200);
    }

    public function getOrderSectors($sectors, $order_sectors)
    {

        foreach ($sectors as $sector) {
            $service = $sector->classification?->organization?->organization_services?->firstWhere('service_id', 1);
            if ($service) {
                $order_sector = $sector->active_order_sector_service($service->id)->first();
                if ($order_sector) {
                    $order_sectors->push($order_sector);
                }
            }
        }
        return $order_sectors;
    }

    public function sectorsOperations()
    {
        $user = Auth::user();
        // $monitor = Monitor::where('user_id', $user->id)->first();
        $order_sectors = collect();
        if ($user->hasRole('boss') || $user->hasRole('supervisor')) {
            if ($user->boss_sectors) {
                $order_sectors = $this->getOrderSectors($user->boss_sectors, $order_sectors);
            }
            if ($user->supervisor_sectors) {
                $order_sectors = $this->getOrderSectors($user->supervisor_sectors, $order_sectors);
            }
        } else {
            return response()->json(['message' => 'not allowed'], 200);
        }
        $order_sector_ids = $order_sectors->pluck('id')->toArray();
        $supports = Support::whereIn('order_sector_id', $order_sector_ids)->get();
        $tickets = Ticket::whereIn('order_sector_id', $order_sector_ids)->get();
        // $submitted_forms = SubmittedForm::whereIn('order_sector_id', $order_sector_ids)->get();//->with('form.sections_has_questions.visible_questions.answers');


        return response()->json([
            'tickets' => ResourcesTicketResource::collection($tickets),
            'supports' => SupportResource::collection($supports),
            // 'submitted_forms' => SubmittedFormResource::collection($submitted_forms),
        ], 200);
    }
}
