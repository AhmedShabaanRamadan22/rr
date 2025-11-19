<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\MealDashboard\MealResource;
use App\Http\Resources\MealDashboard\SupportResource;
use App\Http\Resources\MealDashboard\TicketResource;
use App\Models\Meal;
use App\Models\OrderSector;
use App\Models\Organization;
use App\Models\Sector;
use App\Models\Status;
use App\Models\Support;
use App\Models\Ticket;
use App\Services\OperationSummaryReport;
use App\Traits\PdfTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MealDashboardController extends Controller
{
    use PdfTrait;

    /**
     * Displays the main meal dashboard page for a given organization and date.
     * Show meals for all current registered organizations when the slug is "all"
     */
    public function index(string $organizationSlug, string $date)
    {
        $showAllMeals = $organizationSlug === 'all';
        $organization = $this->getOrganization($organizationSlug, $showAllMeals);
        $this->authorizeAccess($organization, $showAllMeals);

        // Can view meal details including: new supports, new tickets, menu, sector info, stages answers, meal page and report
        $canViewAllDetails = auth()->user()->can('view_all_meals_dashboard_details');
        $isToday = Carbon::parse($date)->isToday();

        $periods = Meal::meal_period();
        $newSupportStatusId = Status::NEW_SUPPORT;
        $waterSupportType = Support::WATER_TYPE;
        $foodSupportType = Support::FOOD_TYPE;

        // Choose organizations which meals are needed in (all meals dashboard) page.
        // organizations should be specified in (fetchMeals) function too
        $organizationIds = $showAllMeals ? Organization::whereIn('slug', ['RWM', 'MCDC', 'ITH', 'IJD', 'VVIP'])->pluck('id')->toArray() : [$organization->id];
        $sectors = $this->getSectorsByOrganizationIds($organizationIds);

        return view('admin.dashboard-meal.meals2', compact(
            'organization', 'periods', 'date', 'showAllMeals', 'newSupportStatusId', 'sectors', 'canViewAllDetails', 'waterSupportType', 'foodSupportType', 'isToday'
        ));
    }

    /**
     * Fetch meals based on date, organization, and period
     * These Meals are shown both in list and board view in meal dashboard
     */
    public function fetchMeals(Request $request)
    {
        $date = $request->input('date');
        $organizationId = $request->organization_id;
        $periodId = $request->period_id;

        // if organization not selected, all meals in current organizations will be fetched
        if ($organizationId == 0) {
            $sectorIds = Sector::whereHas('classification', fn ($q) =>
                $q->whereHas('organization', fn ($q2) =>
                    $q2->whereIn('slug', ['RWM', 'MCDC', 'ITH', 'IJD', 'VVIP']))
            )->pluck('id');
        } else {
            $sectorIds = Sector::whereHas('classification', fn ($q) =>
            $q->where('organization_id', $organizationId)
            )->pluck('id');
        }

        $meals = Meal::with([
            'sector.classification.organization',
            'sector.nationality_organization.nationality:id,name,flag',
            'sector.nationality_organization.organization',
            'sector.boss',
            'sector.supervisor',
            'sector.order_sectors.monitor_order_sectors.monitor.user',
            'meal_organization_stages.organization_stage.stage_bank',
            'meal_organization_stages_arranged.meal.meal_organization_stages',
            'meal_organization_stages_arranged.organization_stage.stage_bank',
            'order_sector.order.facility:id,name',
            'order_sector.sector',
        ])
            ->where(['day_date' => $date, 'period_id' => $periodId])
            ->whereIn('sector_id', $sectorIds)
            ->join('sectors', 'meals.sector_id', '=', 'sectors.id')
            ->orderBy('sectors.label')
            ->select('meals.*', 'sectors.label as sector_label')
            ->get();

        $mealsData = MealResource::collection($meals)->resolve();

        // Group meals by meal organization stage id for board view
        $mealsGroupedByStage = $meals->groupBy(fn ($meal) =>
            optional($meal->current_stage)->organization_stage_id
        );

        $mealsGroupedByStage = $mealsGroupedByStage->map(function ($group) {
            return MealResource::collection($group)->resolve();
        });

        return response()->json([
            'listView' => view('admin.dashboard-meal.partials.list', [
                'data' => $mealsData,
            ])->render(),
            'boardView' => view('admin.dashboard-meal.partials.board', [
                'stages' => $mealsData, 'meals' => $mealsGroupedByStage,
            ])->render(),
        ]);
    }

    /**
     * Fetch only new tickets to show on (new tickets popup)
     */
    public function fetchNewTickets(Request $request)
    {
        $tickets = Ticket::with([
            'order_sector:id,order_id',
            'order_sector.order:id,organization_service_id',
            'order_sector.order.organization_service:id,organization_id',
            'order_sector.order.organization_service.organization:id,slug',
            'reason_danger:id,reason_id,danger_id',
            'reason_danger.reason:id,name',
            'reason_danger.danger:id,color',
            'status:id,name_en,name_ar,color',
            'user:id,name',
        ])
            ->select('id', 'reason_danger_id', 'user_id', 'created_at', 'order_sector_id', 'status_id')
            ->where('order_sector_id', $request->order_sector_id)
            ->new()
            ->latest('created_at')
            ->get();

        return TicketResource::collection($tickets);
    }

    /**
     * Fetch only new supports to show on (new supports popup)
     */
    public function fetchNewSupports(Request $request)
    {
        $supports = Support::with([
            'order_sector:id,order_id,sector_id',
            'order_sector.order:id',
            'order_sector.sector:id,classification_id',
            'order_sector.sector.classification:id,organization_id',
            'order_sector.sector.classification.organization:id,slug',
            'period:id,name',
            'reason_danger:id,reason_id,danger_id',
            'reason_danger.reason:id,name',
            'reason_danger.danger:id,color',
            'user:id,name',
            'status:id,name_en,name_ar,color',
        ])
            ->select('id', 'order_sector_id', 'type', 'period_id', 'reason_danger_id', 'user_id', 'status_id', 'quantity', 'created_at')
            ->where(['meal_id' => $request->meal_id, 'type' => Support::FOOD_TYPE])
            ->orWhere(function ($query) use ($request) {
                $query->where(['order_sector_id' => $request->order_sector_id, 'type' => Support::WATER_TYPE]);
            })
            ->new()
            ->latest('created_at')
            ->get();

        return SupportResource::collection($supports);
    }

    /**
     * Fetch organization by slug, returns Rakaya organization when $showAll is true
     */
    private function getOrganization(string $slug, bool $showAll): Organization
    {
        return Organization::where('slug', $showAll ? 'RKY' : $slug)->firstOrFail();
    }

    private function authorizeAccess(Organization $organization, bool $showAll): void
    {
        if ($showAll) {
            // Can view (all meals dashboard) page which contains all organizations' meals
            $this->authorize('view_all_meals_dashboard');
        } else {
            // Can view meal dashboard page for a specific organization
            $this->authorize('view_meals_dashboard', $organization);
        }
    }

    /**
     * Fetch sectors based on organization, pluck with sector label
     */
    private function getSectorsByOrganizationIds(array $organizationIds): array
    {
        return [
            'sectors' => OrderSector::with([
                'sector:id,label,nationality_organization_id',
                'order.organization_service.service:id,name_ar,name_en',
                'order.facility:id,name',
                'order.organization_service.organization:id,name_ar,name_en',
                'sector.nationality_organization.nationality:id,name',
            ])
                ->whereHas('order.organization_service', fn ($q) =>
                $q->whereIn('organization_id', $organizationIds)
                )
                ->get()
                ->pluck('order_sector_name', 'sector.label')
                ->toArray()
        ];
    }

    /**
     * Generate summary report for all meals in a specific day for an organization
     */
    public function dailyOrganizationOperationalSummaryReport($organization_slug, $date, OperationSummaryReport $service, $output = "I")
    {
        $statuses = $service->getStatuses();
        $dangers = $service->getDangers();
        $meal_statuses = $service->getMealStatuses();
        $organization = Organization::firstWhere('slug', $organization_slug);

        // supports filter and group by functions
        $supportFilterFallback = fn ($q) =>
            $q->whereHas('order_sector.order.organization_service.organization', fn ($q2) => $q2->where('slug', $organization_slug))
                ->whereDate('created_at', $date);
        $supportGroupCallback = fn ($support) => $support->order_sector->sector->label;

        // fetch meals by date
        $meals = $service->getMealsByDate($date, $organization_slug);

        // fetch tickets with specific filtering and grouping by functionalities
        $tickets = $service->aggregateTickets(
            with: ['order_sector.sector', 'reason_danger.danger'],
            filterCallback: fn ($q) => $q->whereHas('order_sector.order.organization_service.organization', function ($q) use ($organization_slug) {
                                            $q->where('slug', $organization_slug);
                                        })->whereDate('created_at', $date),
            groupingCallback: fn ($ticket) => $ticket->order_sector->sector->label
        );

        // fetch water and meal supports
        $meal_supports = $service->aggregateSupports(Support::FOOD_TYPE, $supportFilterFallback, $supportGroupCallback);
        $water_supports = $service->aggregateSupports(Support::WATER_TYPE, $supportFilterFallback, $supportGroupCallback);

        // fetch sectors as keys
        $keys = $service->getKeys($meals, $tickets, $meal_supports, $water_supports);

        // map data
        $finalData = $keys->mapWithKeys(function ($key) use (
            $meals, $tickets, $meal_supports, $water_supports
        ) {
            return [
                $key => [
                    'meals' => $meals->get($key, []),
                    'tickets' => $tickets[$key] ?? [],
                    'meal_supports' => $meal_supports[$key] ?? [
                            'support_count' => 0,
                            'needed_quantity' => 0,
                            'delivered_quantity' => 0,
                        ],
                    'water_supports' => $water_supports[$key] ?? [
                            'support_count' => 0,
                            'needed_quantity' => 0,
                            'delivered_quantity' => 0,
                        ],
                ],
            ];
        })->toArray();

        $this->setPdfData([
            'attachment_label' => 'ملخص تنفيذي',
            'organization_data' => $organization,
            'data' => $finalData,
            'statuses' => $statuses,
            'dangers' => $dangers,
            'date' => $date,
            'meal_statuses' => $meal_statuses,
        ]);
        $mpdf = $this->mPdfInit('meal-dashboard.daily-organization-summary');
        return $mpdf->Output('final report - ' . $organization->name . ' - ' . Carbon::now() . '.pdf', $output);
    }
}
