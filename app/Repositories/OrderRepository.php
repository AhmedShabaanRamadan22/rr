<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Status;

class OrderRepository
{
    /**
     * Base query for users with selected columns
     */
    protected function baseQuery()
    {
        return Order::select(
            'id',
            'status_id',
            'facility_id',
            'organization_service_id',
            'user_id',
        )->with([
            'user',
            'status',
            'facility',
            'organization_service.service',
            'organization_service.organization',
        ]);
    }

    public function allFacilitiesOrdersPaginated(int $facilityId, int $perPage = 20, int $page = 1)
    {
        return $this->baseQuery()
            ->facility($facilityId)
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function create(array $data)
    {
        return Order::create($data);
    }

    public function findActiveOrder(int $facilityId, int $organizationServiceId): ?Order
    {
        return Order::
            where('organization_service_id', $organizationServiceId)
            ->facility($facilityId)
            ->whereIn('status_id', [
                Status::NEW_ORDER,
                Status::PROCESSING_ORDER,
                Status::CONFIRMED_ORDER,
                Status::APPROVED_ORDER,
                Status::ACCEPTED_ORDER,
            ])
            ->first();
    }

    public function updateStatus(Order $order, int $statusId): Order
    {
        $order->update(['status_id' => $statusId]);
        return $order->fresh();
    }
}
