<?php

namespace App\Http\Controllers\External;

use App\Docs\Attributes\ForbiddenErrorResponse;
use App\Docs\Attributes\InternalServerErrorResponse;
use App\Docs\Attributes\NotFoundErrorResponse;
use App\Docs\Attributes\ThrottleRequestErrorResponse;
use App\Docs\Attributes\UnauthorizedErrorResponse;
use App\Docs\Attributes\ValidationErrorResponse;
use App\Exceptions\DuplicateActiveOrderException;
use App\Exceptions\OrderCannotBeCancelledException;
use App\Http\Controllers\Controller;
use App\Http\Requests\External\StoreOrderRequest;
use App\Http\Resources\External\OrderResource;
use App\Models\Facility;
use App\Models\Order;
use App\Services\External\OrderService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\ResponseFromFile;
use Knuckles\Scribe\Attributes\Subgroup;

#[Group("External")]
#[Subgroup("Order")]
#[ForbiddenErrorResponse]
#[UnauthorizedErrorResponse]
#[ThrottleRequestErrorResponse]
#[InternalServerErrorResponse]
class OrderController extends Controller
{
    use ApiResponse;

    public function __construct(protected OrderService $service) {}

    #[BodyParam(name: 'page', type: 'integer', example: 1)]
    #[ValidationErrorResponse([
        'page' => ['page should be integer.', 'page must be at least 1.', 'page must not be greater than 100',],
        'perPage' => ['perPage should be integer.', 'perPage must be at least 1.',]
    ])]
    #[NotFoundErrorResponse]
    public function indexByFacility(Request $request, Facility $facility)
    {
        $validated = $request->validate([
            'perPage' => 'integer|min:1|max:100',
            'page' => 'integer|min:1',
        ]);

        $orders = $this->service->getOrdersByFacility(
            $facility->id,
            $validated['perPage'] ?? 20,
            $validated['page'] ?? 1,
        );

        return $this->successPaginated(
            data: OrderResource::collection($orders),
            paginator: $orders,
            message: "Orders fetched successfully."
        );
    }

    #[ResponseFromFile('responses/order_show.json')]
    #[NotFoundErrorResponse]
    public function show(Facility $facility, Order $order): JsonResponse
    {
        $order->load(['notes', 'user', 'facility', 'organization_service.organization', 'organization_service.service',
            'status', 'notes.note_title', 'notes.user']);
        return $this->success(new OrderResource($order), "Order retrieved successfully.");
    }

    #[Header('Authorization', 'Bearer {YOUR_USER_TOKEN}')]
    #[ResponseFromFile('responses/order_store.json')]
    #[NotFoundErrorResponse]
    #[Response([
        "flag" => false,
        "general_error_message" => "يرجى التواصل مع خدمة العملاء",
        "message" => "An active order already exists for this service."
    ], 422)]
    #[ValidationErrorResponse]
    public function store(StoreOrderRequest $request, Facility $facility): JsonResponse
    {
        try {
            $order = $this->service->create($request->validated(), $facility->id);
            return $this->success(new OrderResource($order), "Order created successfully.");
        } catch (DuplicateActiveOrderException $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    #[Header('Authorization', 'Bearer {YOUR_USER_TOKEN}')]
    #[ResponseFromFile('responses/order_store.json')]
    #[NotFoundErrorResponse]
    #[Response([
        "flag" => false,
        "general_error_message" => "يرجى التواصل مع خدمة العملاء",
        "message" => "Only new orders can be cancelled."
    ], 422)]
    public function cancel(Facility $facility, Order $order): JsonResponse
    {
        try {
            $order = $this->service->cancel($order);
            return $this->success(new OrderResource($order), "Order cancelled successfully.");
        } catch (OrderCannotBeCancelledException $e) {
            return $this->error($e->getMessage(), 422);
        }
    }
}
