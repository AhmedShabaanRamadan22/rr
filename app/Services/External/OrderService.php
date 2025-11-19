<?php

namespace App\Services\External;

use App\Exceptions\DuplicateActiveOrderException;
use App\Exceptions\OrderCannotBeCancelledException;
use App\Models\Order;
use App\Models\Status;
use App\Repositories\OrderRepository;
use App\Traits\AttachmentTrait;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderService
{
    use AttachmentTrait;
    public function __construct(private OrderRepository $repo) {}

    /**
     * Get a paginated list of order for a specific facility.
     */
    public function getOrdersByFacility(int $facilityId, int $perPage = 20, int $page = 1): LengthAwarePaginator
    {
        return $this->repo->allFacilitiesOrdersPaginated($facilityId, $perPage, $page);
    }

    /**
     * @throws DuplicateActiveOrderException
     */
    public function create(array $data, int $facilityId): Order
    {
        // Check for existing active order
        $existingOrder = $this->repo->findActiveOrder(
            $facilityId,
            $data['organization_service_id']
        );

        if ($existingOrder) {
            throw new DuplicateActiveOrderException();
        }

        $orderData = array_merge($data, ['status_id' => Status::NEW_ORDER, 'facility_id' => $facilityId]);

        return $this->repo->create($orderData);
    }

    /**
     * @throws OrderCannotBeCancelledException
     */
    public function cancel(Order $order): Order
    {
        // Check if order can be cancelled
        if ($order->status_id !== Status::NEW_ORDER) {
            throw new OrderCannotBeCancelledException();
        }

        // Update order status to cancelled
        return $this->repo->updateStatus($order, Status::CANCELED_ORDER);
    }
}
