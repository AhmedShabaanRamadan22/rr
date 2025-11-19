<?php

namespace App\Services;

use App\Http\Resources\OrderReports\OperationSummary\MealResource;
use App\Models\Danger;
use App\Models\Meal;
use App\Models\Status;
use App\Models\Support;
use App\Models\Ticket;
use Closure;

class OperationSummaryReport
{
    /**
     * Fetch all statuses for report key colors
     */
    public function getStatuses()
    {
        // statuses are used for key colors
        $statuses = Status::select('color', 'description')->whereIn('type', ['meals'])->get()->map(function ($item) {
            return [
                'color' => $item->color,
                'description' => $item->description,
            ];
        });
        $statuses->push(
            ['color' => '#d0e2f3', 'description' => 'اسم الوجبة'],
            ['color' => '#dfe1e1', 'description' => 'لم يتم البدء في الوجبة بعد'],
            ['color' => '#EE6363', 'description' => 'جاري تحضير الوجبة (متأخر)'],
            ['color' => '#4ab0c1', 'description' => 'جاري تحضير الوجبة (غير متأخر)'],
            ['color' => '#2dcb73', 'description' => 'سلمت الوجبة (غير متأخر)'],
            ['color' => '#EE6363', 'description' => 'سلمت الوجبة (متأخر)'],
        );

        return $statuses;
    }

    /**
     * Fetch supports statistics with dynamic filtering and grouping by callbacks
     */
    public function aggregateSupports($type, $filterCallback, $groupCallback, $supportArrangeCallback = null) {
        $supports = Support::with([
            'assists' => fn ($q) => $q->where('status_id', Status::DELIVERED_ASSIST),
            'order_sector.order.organization_service.organization',
        ])
            ->where('type', $type)
            ->when($filterCallback, fn ($query) => $filterCallback($query))
            ->get()
            ->groupBy($groupCallback);

        $aggregated = collect($supports)->map(function ($group) {
            return [
                'support_count'       => $group->count(),
                'needed_quantity'     => $group->sum('quantity'),
                'delivered_quantity'  => $group->flatMap->assists->sum('quantity'),
            ];
        });

        if ($supportArrangeCallback) {
            $aggregated = $supportArrangeCallback($aggregated);
        }

        return $aggregated->toArray();
    }

    /**
     * Fetch meals statistics for a specific date and organization
     */
    public function getMealsByDate($date, $organization_slug){
        return Meal::with(['order_sector.order.organization_service.organization', 'sector'])
            ->whereHas('order_sector.order.organization_service.organization', function ($q) use ($organization_slug) {
                $q->where('slug', $organization_slug);
            })
            ->whereDate('day_date', $date)
            ->select('id', 'day_date', 'period_id', 'status_id', 'order_sector_id', 'sector_id', 'uuid')
            ->orderBy('period_id')
            ->get()
            ->groupBy(fn($meal) => $meal->sector->label)
            ->map(fn ($group) => MealResource::collection($group)->resolve());
    }

    /**
     * Fetch meals statistics for a specific order sector
     */
    public function getMealsByOrderSector($order_sector_id){
        return Meal::with(['period', 'status'])
            ->where('order_sector_id', $order_sector_id)
            ->select('id', 'day_date', 'period_id', 'status_id', 'order_sector_id', 'uuid')
            ->orderBy('day_date')
            ->get()
            ->groupBy('day_date')
            ->map(fn ($group) => MealResource::collection($group)->resolve());
    }

    /**
     * Fetch ticket statistics with dynamic filtering and grouping by callbacks
     */
    public function aggregateTickets($with, $filterCallback, $groupingCallback): array
    {
        return Ticket::with($with)
            ->when($filterCallback, function ($query) use ($filterCallback) {
                return $filterCallback($query);
            })
            ->whereNot('status_id', Status::FALSE_TICKET)
            ->get()
            ->groupBy($groupingCallback)
            ->map(fn ($group) =>
                $group->groupBy(fn ($ticket) => $ticket->reason_danger->danger_id)
                    ->map(fn ($dangers) => $dangers->count())
                    ->toArray()
                )
            ->toArray();
    }

    /**
     * Fetch danger levels for ticket statistics
     */
    public function getDangers()
    {
        return Danger::whereNot('id', Danger::NO_DANGER)->get();
    }

    /**
     * Fetch array keys for final mapping
     */
    public function getKeys($meals, $tickets, $meal_supports, $water_supports)
    {
        return collect([
            ...$meals->keys(),
            ...array_keys($tickets),
            ...array_keys($tickets),
            ...array_keys($meal_supports),
            ...array_keys($water_supports),
        ])->unique()->sort()->values();
    }

    public function getMealStatuses()
    {
        return [
            'not-started' => ['caption' => 'لم يتم البدء في التحضير', 'color' => '#dfe1e1'],
            'in-progress late' => ['caption' => 'جاري التحضير (متأخر)', 'color' => '#EE6363'],
            'in-progress on-time' => ['caption' => 'جاري التحضير (غير متأخر)', 'color' => '#4ab0c1'],
            'done on-time' => ['caption' => 'سلمت الوجبة (غير متأخر)', 'color' => '#2dcb73'],
            'done late' => ['caption' => 'سلمت الوجبة (متأخر)', 'color' => '#EE6363'],
        ];
    }
}
