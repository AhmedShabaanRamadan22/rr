<?php

namespace App\Http\Controllers\Admin;

use Alkoumi\LaravelHijriDate\Hijri;
use App\Http\Resources\OrderReports\OperationSummary\MealResource;
use App\Models\Meal;
use App\Models\Order;
use App\Models\Ticket;
use App\Services\OperationSummaryReport;
use App\Traits\PdfTrait;
use Illuminate\Http\Request;
use App\Models\AttachmentLabel;
use App\Http\Controllers\Controller;
use App\Models\Danger;
use App\Models\Fine;
use App\Models\OrderSector;
use App\Models\Sector;
use App\Models\Status;
use App\Models\SubmittedForm;
use App\Models\Support;
use Carbon\Carbon;
use App\Services\AnswerService;
use Illuminate\Support\Facades\DB;

class OrderReportController extends Controller
{
    //
    use PdfTrait;

    public function index()
    {
        $columns = Order::orderReportColumns();
        $columnInputs = Order::columnInput();
        // $filterColumns = Order::orderReportFilterColumns();
        return view('admin.order_reports.index', compact('columns', 'columnInputs'));
    }

    public function orderPdfReport($order_uuid, $output = "I")
    {
        // dd($order_uuid, Order::where('uuid', '7701f5d8-262c-48a1-bc29-a8da51da20c2')->get()->toArray());
        $order = Order::where('uuid', $order_uuid)->firstOrFail();
        $this->setPdfData([
            'attachment_label' => 'تقرير عن حالة طلب',
            'organization_data' => $order->organization_service->organization,
            'body_content' => $order,
        ]);
        $mpdf = $this->mPdfInit('order_details.order');
        return $mpdf->Output($order->code . ' - ' .  $order->facility->name . ' - ' . Carbon::now() . '.pdf', $output);
    }

    public function facilityPdfReport($order_uuid, $output = "I")
    {
        $order = Order::where('uuid', $order_uuid)->firstOrFail();
        $facility = $order->facility;
        $attachments_label = AttachmentLabel::where('type', 'facility_employees')->get();
        $this->setPdfData([
            'attachment_label' => 'تقرير عن حالة طلب',
            'organization_data' => $order->organization_service->organization,
            'body_content' => $order,
            'facility' => $facility,
            'employee_attachment_lables' => $attachments_label,
        ]);
        $mpdf = $this->mPdfInit('order_details.facility');
        return $mpdf->Output($facility->id . ' - ' . $facility->name . ' - ' . Carbon::now() . '.pdf', $output);
    }

    public function ticketPdfReport($order_uuid, $order_sector_id, $output = "I")
    {
        $order = Order::where('uuid', $order_uuid)->firstOrFail();
        $order_sector = OrderSector::withArchived()->findOrFail($order_sector_id);
        $tickets = Ticket::where('order_sector_id', $order_sector_id)->get();
        $statuses = Status::where('type', 'tickets')->get();
        $danger_levels = Danger::get();
        $this->setPdfData([
            'attachment_label' => 'تقرير بلاغ',
            'organization_data' => $order->organization_service->organization,
            'tickets' => $tickets,
            'order_sector' => $order_sector,
            'danger_levels' => $danger_levels,
            'statuses' => $statuses,
            'model' => $order,
            // 'tickets' => $tickets
        ]);
        $mpdf = $this->mPdfInit('order_details.ticket');
        return $mpdf->Output('tickets report - ' . $order->facility->name . ' - '. Carbon::now() . '.pdf', $output);
    }
    public function supportPdfReport($order_uuid, $order_sector_id, $output = "I")
    {
        $order = Order::where('uuid', $order_uuid)->firstOrFail();
        $order_sector = OrderSector::withArchived()->findOrFail($order_sector_id);
        $supports = Support::where('order_sector_id', $order_sector_id)->get();
        $statuses = Status::where('type', 'supports')->get();
        $danger_levels = Danger::get();
        $this->setPdfData([
            'attachment_label' => 'تقرير إسناد',
            'organization_data' => $order->organization_service->organization,
            'supports' => $supports,
            'statuses' => $statuses,
            'danger_levels' => $danger_levels,
            'order_sector' => $order_sector,
            'model' => $order,
        ]);
        $mpdf = $this->mPdfInit('order_details.support');
        return $mpdf->Output('supports report - ' . $order->facility->name . ' - ' . Carbon::now() . '.pdf', $output);
    }
    public function finePdfReport($order_uuid, $order_sector_id, $output = "I")
    {
        $order = Order::where('uuid', $order_uuid)->firstOrFail();
        $order_sector = OrderSector::withArchived()->findOrFail($order_sector_id);
        $fines = Fine::where('order_sector_id', $order_sector_id)->get();
        // dd($order_sector->id);
        $this->setPdfData([
            'attachment_label' => 'تقرير المخالفات',
            'organization_data' => $order->organization_service->organization,
            'fines' => $fines,
            'order_sector' => $order_sector,
            'model' => $order,
        ]);
        $mpdf = $this->mPdfInit('order_details.fine');
        return $mpdf->Output('fines report - ' . $order->facility->name . ' - ' . Carbon::now() . '.pdf', $output);
    }

    public function mealPdfReport($order_uuid, $order_sector_id, $output = "I")
    {
        $order = Order::where('uuid', $order_uuid)->firstOrFail();
        $order_sector = OrderSector::withArchived()->findOrFail($order_sector_id);
        $statuses = Status::where('type', 'meals')->get();
        $meals = Meal::where('order_sector_id', $order_sector->id)->get();
        // dd($meal);
        $this->setPdfData([
            'attachment_label' => 'تقرير الوجبات',
            'organization_data' => $order->organization_service->organization,
            'meals' => $meals,
            'order_sector' => $order_sector,
            'statuses' => $statuses,
            'model' => $order,
        ]);
        $mpdf = $this->mPdfInit('order_details.meal');
        return $mpdf->Output('meals report - ' . $order->facility->name . ' - ' . Carbon::now() . '.pdf', $output);
    }

    /**
     * Operational summary report for each order sector
     */
    public function operationSummaryReport($order_uuid, $order_sector_id, OperationSummaryReport $service, $output = "I")
    {
        $order = Order::where('uuid', $order_uuid)->firstOrFail();
        $order_sector = OrderSector::withArchived()->findOrFail($order_sector_id);

        $statuses = $service->getStatuses();
        $dangers = $service->getDangers();
        $meal_statuses = $service->getMealStatuses();

        // support filtering and grouping by callbacks
        $supportFilterFallback = fn ($q) => $q->where('order_sector_id', $order_sector->id);
        $supportGroupCallback = fn ($support) => $support->created_at->toDateString();
        $supportArrangeCallback = fn ($supports) => $supports
            ->sortBy(fn ($support, $date) => $date)
            ->keyBy(fn ($support, $date) => $date)
            ->map(fn ($support) => (array) $support);

        // fetch meals per order sector
        $meals = $service->getMealsByOrderSector($order_sector->id);

        // fetch ticket statistics for order sector
        $tickets = $service->aggregateTickets(
            with: ['reason_danger.danger'],
            filterCallback: fn ($q) => $q->where('order_sector_id', $order_sector->id),
            groupingCallback: fn ($ticket) => $ticket->created_at->toDateString(),
        );

        // fetch support statistics for order sector
        $meal_supports = $service->aggregateSupports(Support::FOOD_TYPE, $supportFilterFallback, $supportGroupCallback, $supportArrangeCallback);
        $water_supports = $service->aggregateSupports(Support::WATER_TYPE, $supportFilterFallback, $supportGroupCallback, $supportArrangeCallback);

        // get dates as keys
        $allDates = $service->getKeys($meals, $tickets, $meal_supports, $water_supports);
        // get corresponding hijri dates for date keys
        $hijriDates = $allDates->mapWithKeys(function ($gregorianDate) {
            $hijriDate = Hijri::Date('Y-m-d', $gregorianDate);
            return [$hijriDate => $gregorianDate];
        });

        // map data
        $finalData = $hijriDates->mapWithKeys(function ($gregorianDate, $hijriDate) use (
            $meals, $tickets, $meal_supports, $water_supports
        ) {
            return [
                $hijriDate => [
                    'meals' => $meals->get($gregorianDate, []),
                    'tickets' => $tickets[$gregorianDate] ?? [],
                    'meal_supports' => $meal_supports[$gregorianDate] ?? [
                            'support_count' => 0,
                            'needed_quantity' => 0,
                            'delivered_quantity' => 0,
                        ],
                    'water_supports' => $water_supports[$gregorianDate] ?? [
                            'support_count' => 0,
                            'needed_quantity' => 0,
                            'delivered_quantity' => 0,
                        ],
                ],
            ];
        })->toArray();

        $this->setPdfData([
            'organization_data' => $order->organization_service->organization,
            'data' => $finalData,
            'statuses' => $statuses,
            'dangers' => $dangers,
            'meal_statuses' => $meal_statuses,
            'order_sector' => $order_sector,
            'model' => $order,
        ]);
        $mpdf = $this->mPdfInit('order_details.final-report');
        return $mpdf->Output('final report - ' . $order->facility->name . ' - ' . Carbon::now() . '.pdf', $output);
    }

    public function submittedFormPdfReport($submitted_form_id, $output = "I")
    {
        // $order = Order::where('uuid', $order_uuid)->firstOrFail();
        // $order_sector = OrderSector::withArchived()->findOrFail($order_sector_id);
        $answer_service = new AnswerService();
        $submitted_form = SubmittedForm::findOrFail($submitted_form_id);
        // dd($meal);
        $this->setPdfData([
            'attachment_label' => 'تقرير الاستمارات المسلمة',
            'organization_data' => $submitted_form->order_sector->order->organization_service->organization,
            'submitted_form' => $submitted_form,
            'order_sector' => $submitted_form->order_sector,
            'answer_service' => $answer_service,
            'model' => $submitted_form,
        ]);
        $mpdf = $this->mPdfInit('order_details.submitted-form');
        return $mpdf->Output('submitted forms report - ' . $submitted_form->order_sector->order->facility->name . ' - ' . Carbon::now() . '.pdf', $output);
    }

    public function datatable(Request $request)
    {
        $query = Order::with('organization_service');
        // $query->whereHas('facility', function ($q) {
        //     $q->whereNull('deleted_at');
        // });

        if ($request->facility_id) {
            $query->whereIn('facility_id', $request->facility_id);
        }
        if ($request->organization_id) {
            $query->whereHas('organization_service', function ($q) use ($request) {
                $q->whereIn('organization_id', $request->organization_id);
            });
        }
        if ($request->status_id) {
            $query->whereIn('status_id', $request->status_id);
        }

        // ini_set('memory_limit', '256M');

        return datatables($query->orderByDesc('created_at')->get())
            ->editColumn('code', function (Order $order) {
                return $order->code ?? '-';
            })
            ->editColumn('organization_name', function (Order $order) {
                return $order->organization_service->organization->name_ar ?? '-';
            })
            ->editColumn('user-name', function (Order $order) {
                return $order->user->name ?? '-';
            })
            ->editColumn('facility-name', function (Order $order) {
                return $order->facility->name;
            })
            ->editColumn('status', function (Order $order) {
                return "<span class='badge ' style='background:" . $order->status->color . "' >" . $order->status->name . "</span>";
            })
            ->editColumn('order-reports', function (Order $order) {
                return '<a
                    class="btn btn-outline-primary btn-sm m-1 on-default "
                    href="' . (route('admin.orders.report', ($order->uuid ?? fakeUuid()))) . '"
                    target="_blank"
                    ><i class="mdi mdi-file-document-outline"></i>
                    </a>' .
                    '<a target="_blank"
                    class="btn btn-outline-success btn-sm m-1 on-default "
                    href="' . (route('admin.orders.report', [$order->uuid ?? fakeUuid(), 'D'])) . '"
                    ><i class="mdi mdi-download-outline"></i>
                    </a>';
            })
            ->editColumn('facility-reports', function (Order $order) {
                return '<a
                    class="btn btn-outline-primary btn-sm m-1 on-default "
                    href="' . (route('admin.order-details.facility-report', $order->uuid ?? fakeUuid())) . '"
                    target="_blank"
                    ><i class="mdi mdi-file-document-outline"></i>
                    </a>' .
                    '<a target="_blank"
                    class="btn btn-outline-success btn-sm m-1 on-default "
                    href="' . (route('admin.order-details.facility-report', [$order->uuid ?? fakeUuid(), 'D'])) . '"
                    ><i class="mdi mdi-download-outline"></i>
                    </a>';
            })
            ->editColumn('ticket-reports', function (Order $order) {
                if ($order->active_order_sectors()->isNotEmpty()) {
                    $html = '';
                    foreach ($order->active_order_sectors() as $order_sector) {
                        $html .= '<div class="flex-row">
                        <span class="badge bg-primary">' . $order_sector->sector->label . '</span>
                            <a
                                class="btn btn-outline-primary btn-sm m-1 on-default "
                                href="' . (route('admin.order-details.ticket-report', [$order->uuid ?? fakeUuid(), $order_sector->id])) . '"
                                target="_blank"
                                ><i class="mdi mdi-file-document-outline"></i>
                            </a>' .
                            '<a target="_blank"
                                class="btn btn-outline-success btn-sm m-1 on-default "
                                href="' . (route('admin.order-details.ticket-report', [$order->uuid ?? fakeUuid(), $order_sector->id, 'D'])) . '"
                                ><i class="mdi mdi-download-outline"></i>
                            </a>
                        </div>';
                    }
                    return $html;
                }
                return trans('translation.no-active-order-sector');
            })
            ->editColumn('operation-summary-reports', function (Order $order) {
                if ($order->active_order_sectors()->isNotEmpty()) {
                    $html = '';
                    foreach ($order->active_order_sectors() as $order_sector) {
                        $html .= '<div class="flex-row">
                        <span class="badge bg-primary">' . $order_sector->sector->label . '</span>
                            <a
                                class="btn btn-outline-primary btn-sm m-1 on-default "
                                href="' . (route('admin.order-details.operation-summary-report', [$order->uuid ?? fakeUuid(), $order_sector->id])) . '"
                                target="_blank"
                                ><i class="mdi mdi-file-document-outline"></i>
                            </a>' .
                            '<a target="_blank"
                                class="btn btn-outline-success btn-sm m-1 on-default "
                                href="' . (route('admin.order-details.operation-summary-report', [$order->uuid ?? fakeUuid(), $order_sector->id, 'D'])) . '"
                                ><i class="mdi mdi-download-outline"></i>
                            </a>
                        </div>';
                    }
                    return $html;
                }
                return trans('translation.no-active-order-sector');
            })
            ->editColumn('support-reports', function (Order $order) {
                if ($order->active_order_sectors()->isNotEmpty()) {
                    $html = '';
                    foreach ($order->active_order_sectors() as $order_sector) {
                        $html .= '<div class="flex-row">
                        <span class="badge bg-primary">' . $order_sector->sector->label . '</span>
                            <a
                                class="btn btn-outline-primary btn-sm m-1 on-default "
                                href="' . (route('admin.order-details.support-report', [$order->uuid ?? fakeUuid(), $order_sector->id])) . '"
                                target="_blank"
                                ><i class="mdi mdi-file-document-outline"></i>
                            </a>' .
                            '<a target="_blank"
                                class="btn btn-outline-success btn-sm m-1 on-default "
                                href="' . (route('admin.order-details.support-report', [$order->uuid ?? fakeUuid(), $order_sector->id, 'D'])) . '"
                                ><i class="mdi mdi-download-outline"></i>
                            </a>
                        </div>';
                    }
                    return $html;
                }
                return trans('translation.no-active-order-sector');
            })
            ->editColumn('fine-reports', function (Order $order) {
                if ($order->active_order_sectors()->isNotEmpty()) {
                    $html = '';
                    foreach ($order->active_order_sectors() as $order_sector) {
                        $html .= '<div class="flex-row">
                        <span class="badge bg-primary">' . $order_sector->sector->label . '</span>
                            <a
                                class="btn btn-outline-primary btn-sm m-1 on-default "
                                href="' . (route('admin.order-details.fine-report', [$order->uuid ?? fakeUuid(), $order_sector->id])) . '"
                                target="_blank"
                                ><i class="mdi mdi-file-document-outline"></i>
                            </a>' .
                            '<a target="_blank"
                                class="btn btn-outline-success btn-sm m-1 on-default "
                                href="' . (route('admin.order-details.fine-report', [$order->uuid ?? fakeUuid(), $order_sector->id, 'D'])) . '"
                                ><i class="mdi mdi-download-outline"></i>
                            </a>
                        </div>';
                    }
                    return $html;
                }
                return trans('translation.no-active-order-sector');
            })
            ->editColumn('meal-reports', function (Order $order) {
                if ($order->active_order_sectors()->isNotEmpty()) {
                    $html = '';
                    foreach ($order->active_order_sectors() as $order_sector) {
                        $html .= '<div class="flex-row">
                        <span class="badge bg-primary">' . $order_sector->sector->label . '</span>
                            <a
                                class="btn btn-outline-primary btn-sm m-1 on-default "
                                href="' . (route('admin.order-details.meal-report', [$order->uuid ?? fakeUuid(), $order_sector->id])) . '"
                                target="_blank"
                                ><i class="mdi mdi-file-document-outline"></i>
                            </a>' .
                            '<a target="_blank"
                                class="btn btn-outline-success btn-sm m-1 on-default "
                                href="' . (route('admin.order-details.meal-report', [$order->uuid ?? fakeUuid(), $order_sector->id, 'D'])) . '"
                                ><i class="mdi mdi-download-outline"></i>
                            </a>
                        </div>';
                    }
                    return $html;
                }
                return trans('translation.no-active-order-sector');
            })
            ->editColumn('submitted-form-reports', function (Order $order) {
                if ($order->active_order_sectors()->isNotEmpty()) {
                    $html = '';
                    foreach ($order->active_order_sectors() as $order_sector) {
                        $html .= '<div class="flex-row">
                        <span class="badge bg-primary">' . $order_sector->sector->label . '</span>
                            <a
                                class="btn btn-outline-primary btn-sm m-1 on-default "
                                href="' . (route('admin.submitted-forms.store-order-sector-id', ['order_sector_id' => $order_sector->id])) . '"

                                >' . trans('translation.submitted-forms') . '
                            </a>
                            </div>';
                            // <a
                            //     class="btn btn-outline-primary btn-sm m-1 on-default "
                            //     href="' . (route('admin.order-details.submitted-form-report', [$order->uuid ?? fakeUuid(), $order_sector->id])) . '"
                            //     target="_blank"
                            //     ><i class="mdi mdi-file-document-outline"></i>
                            // </a>' .
                            // '<a target="_blank"
                            //     class="btn btn-outline-success btn-sm m-1 on-default "
                            //     href="' . (route('admin.order-details.submitted-form-report', [$order->uuid ?? fakeUuid(), $order_sector->id, 'D'])) . '"
                            //     ><i class="mdi mdi-download-outline"></i>
                            // </a>
                    }
                    return $html;
                }
                return trans('translation.no-active-order-sector');
            })
            ->rawColumns(['status', 'order-reports', 'facility-reports', 'support-reports', 'ticket-reports', 'fine-reports', 'meal-reports', 'submitted-form-reports', 'operation-summary-reports'])
            ->toJson();
    }
}
