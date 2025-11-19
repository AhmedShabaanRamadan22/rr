<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectorRequest;
use App\Http\Resources\StatusResource;
use App\Http\Resources\Supervisor\SupervisorSectorResource;
use App\Http\Resources\Supervisor\SupervisorTicketResource;
use App\Http\Resources\Supervisor\SupervisorSupportResource;
use App\Http\Resources\TicketResource;
use App\Models\OrderSector;
use App\Models\Organization;
use App\Models\Sector;
use App\Models\Status;
use App\Models\Support;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class SupervisorController extends Controller
{

    public function statistics()
    {
        $supervisor = auth()->user();

        if (!$supervisor || !$supervisor->is_boss_or_supervisor) {
            return response(['message' => trans('translation.This action is unauthorized.')], 403);
        }

        $tickets_query = Ticket::bySupervisor($supervisor->id);
        $supports_query = Support::bySupervisor($supervisor->id);
        $meal_supports_query = (clone $supports_query)->where('type', 2);
        $water_supports_query = (clone $supports_query)->where('type', 3);
        $data = [
            // tickets
            'tickets' => [
                'total_tickets' => $tickets_query->count(),
                'total_tickets_today' => (clone $tickets_query)->today()->count(),
                'total_tickets_closed' => (clone $tickets_query)->closed()->count(),
                'total_tickets_not_closed' => (clone $tickets_query)->notClosed()->count(),
                'total_tickets_wrong' => (clone $tickets_query)->wrong()->count(),
            ],

            // meals support
            'meal_supports' => [
                'total_meal_supports_count' => $meal_supports_query->count(),
                'total_meal_supports_quantity' => (clone $meal_supports_query)->sum('quantity'),
                'total_meal_supports_count_today' => (clone $meal_supports_query)->today()->count(),
                'total_meal_supports_quantity_today' => (clone $meal_supports_query)->today()->sum('quantity'),
                'total_meal_supports_closed' => (clone $meal_supports_query)->closed()->count(),
                'total_meal_supports_not_closed' => (clone $meal_supports_query)->notClosed()->count(),
            ],

            // water support
            'water_supports' => [
                'total_water_supports_count' => $water_supports_query->count(),
                'total_water_supports_quantity' => (clone $water_supports_query)->sum('quantity'),
                'total_water_supports_count_today' => (clone $water_supports_query)->today()->count(),
                'total_water_supports_quantity_today' => (clone $water_supports_query)->today()->sum('quantity'),
                'total_water_supports_closed' => (clone $water_supports_query)->closed()->count(),
                'total_water_supports_not_closed' => (clone $water_supports_query)->notClosed()->count(),
            ],


        ];

        return response(['data' => $data], 200);
    }

    public function all_tickets(Request $request)
    {
        $supervisor = auth()->user();
        if (!($supervisor && $supervisor->is_boss_or_supervisor)) {
            return response(['message' => trans('translation.This action is unauthorized.')], 403);
        }

        $tickets = Ticket::bySupervisor($supervisor->id)
            ->with([
                'order_sector.sector.classification.organization',
                'notes',
                'attachments.attachment_label',
                'reason_danger' => [
                    'danger',
                    'reason'
                ],
                'status',
                'user',
                'order_sector.order.facility'

            ])
            ->orderBy('updated_at', 'desc')
            ->paginate($request->per_page ?? 5);

        $statuses = Status::select(['id', 'name_ar', 'name_en', 'color'])->where('type', 'tickets')->get();
        $sectors = Sector::where('supervisor_id', $supervisor->id)
            ->ActiveOrderSectorByServiceId()
            ->with('classification')
            ->get();

        return response()->json([
            'filters' => [
                'statuses' => $statuses,
                'sectors' => SupervisorSectorResource::collection($sectors),
            ],
            'tickets' => SupervisorTicketResource::collection($tickets),
            'pages' => $tickets->lastPage()
        ], 200);
    }

    public function all_supports(Request $request)
    {
        $supervisor = auth()->user();
        if (!($supervisor && $supervisor->is_boss_or_supervisor)) {
            return response(['message' => trans('translation.This action is unauthorized.')], 403);
        }

        $supports = Support::bySupervisor($supervisor->id)
            ->with([
                'reason_danger' => [
                    'reason',
                    'danger'
                ],
                'status',
                'assists' => [
                    'status',
                    'attachments',
                    'assistant' => [
                        'profile_photo_attachment',
                        'country',
                    ],
                    'assigner' => [
                        'profile_photo_attachment',
                        'country'
                    ]
                ],
                'period',
                'order_sector' => [
                    'order',
                    'sector.classification.organization',
                ],
                'notes.user',
                'attachments.attachment_label',
            ])
            ->orderBy('updated_at', 'desc')
            ->paginate($request->per_page ?? 5);

        $statuses = Status::select(['id', 'name_ar', 'name_en', 'color'])->where('type', 'supports')->get();
        $sectors = Sector::where('supervisor_id', $supervisor->id)
            ->ActiveOrderSectorByServiceId()
            ->with('classification')
            ->get();

        return response()->json([
            'filters' => [
                'support_types' => [
                    ['id' => 2, 'name_ar' => trans('translation.Food'), 'name_en' => 'Food'],
                    ['id' => 3, 'name_ar' => trans('translation.Water'), 'name_en' => 'Water'],
                ],
                'statuses' => $statuses,
                'sectors' => SupervisorSectorResource::collection($sectors),

            ],
            'supports' => SupervisorSupportResource::collection($supports),
            'pages' => $supports->lastPage()
        ], 200);
    }
}
