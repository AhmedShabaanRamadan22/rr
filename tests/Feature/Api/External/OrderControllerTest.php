<?php

namespace Feature\Api\External;

use App\Models\Facility;
use App\Models\Order;
use App\Models\OrganizationService;
use App\Models\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Tests\Traits\ExternalApiRequests;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase, ExternalApiRequests;

    protected Facility $facility;
    protected Status $status;
    protected Status $cancelledStatus;
    protected OrganizationService $organizationService;
    protected function setUp(): void
    {
        $this->setUpExternalApi();

        $this->facility = Facility::factory()->create();
        $this->status = Status::factory()->create(['id' => Status::NEW_ORDER,'type' => 'orders']);
        $this->cancelledStatus = Status::factory()->create(['id' => Status::CANCELED_ORDER,'type' => 'orders']);
        Status::factory()->create(['id' => Status::PROCESSING_ORDER,'type' => 'orders']);
        Status::factory()->create(['id' => Status::CONFIRMED_ORDER,'type' => 'orders']);
        Status::factory()->create(['id' => Status::APPROVED_ORDER,'type' => 'orders']);
        Status::factory()->create(['id' => Status::ACCEPTED_ORDER,'type' => 'orders']);
        Status::factory()->create(['id' => Status::REJECTED_ORDER,'type' => 'orders']);
        $this->organizationService = OrganizationService::factory()->create();
    }

    protected function createOrders(int $count = 1, ?int $facilityId = null, $statusId = null, $organizationServiceId = null): Model | Collection
    {
        $orders = Order::factory()
            ->count($count)
            ->create([
                'user_id' => $this->user->id,
                'facility_id' => $facilityId ?? $this->facility->id,
                'status_id' => $statusId ?? $this->status->id,
                'organization_service_id' => $organizationServiceId ?? $this->organizationService->id,
            ]);

        return $count === 1 ? $orders->first() : $orders;
    }

    protected function assertOrderJsonStructure($response): void
    {
        $response->assertJsonStructure([
            'flag',
            'status',
            'message',
            'data' => [
                '*' => ['id', 'user_name', 'organization', 'service', 'status', 'facility'],
            ],
            'meta' => ['current_page', 'last_page', 'per_page', 'total'],
            'links' => ['first', 'last', 'prev', 'next']
        ])->assertJson([
            'flag' => true,
            'status' => 'success',
            'message' => 'Orders fetched successfully.',
        ]);
    }

    public static function invalidPaginationProvider(): array
    {
        return [
            'perPage not numeric' => ['perPage', 'abc'],
            'perPage min value'   => ['perPage', 0],
            'perPage max value'   => ['perPage', 101],
            'perPage negative'    => ['perPage', -5],
            'page not numeric'    => ['page', 'abc'],
            'page min value'      => ['page', 0],
            'page negative'       => ['page', -5],
        ];
    }

    // ============================================================
    // INDEX BY FACILITY TESTS (Public Facility's Orders)
    // ============================================================

    public function test_index_by_facility_returns_paginated_order_with_default_pagination()
    {
        $this->createOrders(21);

        $response = $this->getWithApiToken("/external/facilities/{$this->facility->id}/orders");

        $response->assertOk();
        $this->assertOrderJsonStructure($response);
        $response->assertJsonCount(20, 'data')
            ->assertJsonPath('meta.total', 21);
    }

    public function test_index_by_facility_only_returns_required_facility_order()
    {
        $anotherFacility = Facility::factory()->create();

        $facilityOrders = $this->createOrders(2);
        $this->createOrders(1, $anotherFacility->id);

        $response = $this->getWithApiToken("/external/facilities/{$this->facility->id}/orders");

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.total', 2);

        $returnedIds = collect($response->json('data'))->pluck('id')->toArray();
        $expectedIds = $facilityOrders->pluck('id')->toArray();

        $this->assertEquals(sort($expectedIds), sort($returnedIds));
    }

    public function test_index_by_facility_supports_custom_pagination()
    {
        $this->createOrders(6);

        // Custom perPage
        $response = $this->getWithApiToken("/external/facilities/{$this->facility->id}/orders?perPage=5");
        $response->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('meta.per_page', 5);

        // Custom page
        $response = $this->getWithApiToken("/external/facilities/{$this->facility->id}/orders?perPage=3&page=2");
        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonPath('meta.current_page', 2);
    }

    /**
     * @dataProvider invalidPaginationProvider
     */
    public function test_index_by_facility_validates_pagination_parameters(string $param, mixed $value)
    {
        $response = $this->getAsUser("/external/facilities/{$this->facility->id}/orders?{$param}={$value}");

        $response->assertStatus(422)->assertJsonValidationErrors($param);
    }

    public function test_index_by_facility_returns_empty_data_when_facility_has_no_order()
    {
        $anotherFacility = Facility::factory()->create();
        $this->createOrders(1, $anotherFacility->id);

        $response = $this->getWithApiToken("/external/facilities/{$this->facility->id}/orders");

        $response->assertOk()
            ->assertJsonCount(0, 'data')
            ->assertJsonPath('meta.total', 0);
    }

    public function test_index_my_facilities_includes_relationship_data()
    {
        $order = $this->createOrders();

        $response = $this->getWithApiToken("/external/facilities/{$this->facility->id}/orders");

        $response->assertOk();
        $data = $response->json('data.0');

        $this->assertArrayHasKey('user_name', $data);
        $this->assertEquals($order->user->name, $data['user_name']);

        $this->assertArrayHasKey('organization', $data);
        $this->assertEquals($order->organization->name, $data['organization']);

        $this->assertArrayHasKey('service', $data);
        $this->assertEquals($order->service->name, $data['service']);

        $this->assertArrayHasKey('status', $data);
        $this->assertEquals($order->status->name, $data['status']);

        $this->assertArrayHasKey('facility', $data);
        $this->assertEquals($order->facility->name, $data['facility']);
    }

    public function test_index_by_facility_returns_404_for_non_existent_facility()
    {
        $response = $this->getWithApiToken("/external/facilities/999999/orders");

        $response->assertNotFound();
    }

    // ============================================================
    // SHOW TESTS (Single Order)
    // ============================================================

    public function test_show_returns_single_order_for_a_specific_facility_successfully()
    {
        $order = $this->createOrders();
        $order->notes()->create(['user_id' => $this->user->id, 'content' => 'test adding a note']);

        $response = $this->getWithApiToken("/external/facilities/{$this->facility->id}/orders/{$order->id}");

        $response->assertOk()
            ->assertJsonStructure([
                'flag',
                'status',
                'message',
                'data' => ['id', 'user_name', 'organization', 'service', 'status', 'facility',
                        'notes' => ['*' => ['id', 'content', 'user_name', 'created_at']],
                ],
            ]);
    }

    public function test_show_includes_relationship_data()
    {
        $order = $this->createOrders();

        $response = $this->getWithApiToken("/external/facilities/{$this->facility->id}/orders/{$order->id}");

        $response->assertOk();
        $data = $response->json('data');

        $this->assertArrayHasKey('user_name', $data);
        $this->assertEquals($order->user->name, $data['user_name']);

        $this->assertArrayHasKey('organization', $data);
        $this->assertEquals($order->organization->name, $data['organization']);

        $this->assertArrayHasKey('service', $data);
        $this->assertEquals($order->service->name, $data['service']);

        $this->assertArrayHasKey('status', $data);
        $this->assertEquals($order->status->name, $data['status']);

        $this->assertArrayHasKey('facility', $data);
        $this->assertEquals($order->facility->name, $data['facility']);
    }

    public function test_show_includes_notes_data_correctly()
    {
        $order = $this->createOrders();
        $orderNote = $order->notes()->create(['user_id' => $this->user->id, 'content' => 'test adding a note']);

        $response = $this->getWithApiToken("/external/facilities/{$this->facility->id}/orders/{$order->id}");

        $response->assertOk();
        $note = $response->json('data.notes.0');

        $this->assertArrayHasKey('id', $note);
        $this->assertArrayHasKey('content', $note);
        $this->assertArrayHasKey('user_name', $note);
        $this->assertArrayHasKey('created_at', $note);

        $this->assertEquals($orderNote->id, $note['id']);
        $this->assertEquals($orderNote->content, $note['content']);
        $this->assertEquals($orderNote->user->name, $note['user_name']);
    }

    public function test_show_returns_404_for_order_not_belong_to_facility()
    {
        $order = $this->createOrders();

        $response = $this->getWithApiToken("/external/facilities/9999/orders/{$order->id}");

        $response->assertNotFound();
    }

    public function test_show_returns_404_for_non_existent_order()
    {
        $response = $this->getWithApiToken("/external/facilities/{$this->facility->id}/orders/99999");

        $response->assertNotFound();
    }


    // ============================================================
    // STORE ENDPOINT
    // ============================================================

    public function test_store_creates_order_successfully_with_valid_data(): void
    {
        $response = $this->postAsUser(
            "/external/facilities/{$this->facility->id}/orders",
            data: ['organization_service_id' => $this->organizationService->id]
        );

        $response->assertOk()
            ->assertJsonStructure([
                'flag',
                'status',
                'message',
                'data' => ['id', 'code', 'user_name', 'organization', 'service', 'status', 'facility', 'updated_at', 'created_at',]
            ])
            ->assertJson([
                'flag' => true,
                'status' => 'success',
                'message' => 'Order created successfully.',
                'data' => [
                    'user_name' => $this->user->name,
                    'organization' => $this->organizationService->organization->name,
                    'service' => $this->organizationService->service->name,
                    'status' => $this->status->name,
                    'facility' => $this->facility->name,
                ]
            ]);

        // Verify database
        $this->assertDatabaseHas('orders', [
            'organization_service_id' => $this->organizationService->id,
            'facility_id' => $this->facility->id,
            'user_id' => $this->user->id,
            'status_id' => Status::NEW_ORDER,
        ]);
    }

    public function test_store_automatically_sets_authenticated_user_id(): void
    {
        // Act
        $response = $this->postAsUser(
            "/external/facilities/{$this->facility->id}/orders",
            data: ['organization_service_id' => $this->organizationService->id]
        );

        $response->assertOk();

        $order = Order::latest()->first();
        $this->assertEquals($this->user->id, $order->user_id);
    }

    public function test_store_sets_default_status_to_new_order(): void
    {
        // Act
        $response = $this->postAsUser(
            "/external/facilities/{$this->facility->id}/orders",
            data: ['organization_service_id' => $this->organizationService->id]
        );

        $response->assertOk();

        $order = Order::latest()->first();
        $this->assertEquals(Status::NEW_ORDER, $order->status_id);
    }

    public function test_store_generates_order_code_automatically(): void
    {
        // Act
        $response = $this->postAsUser(
            "/external/facilities/{$this->facility->id}/orders",
            data: ['organization_service_id' => $this->organizationService->id]
        );

        $response->assertOk();

        $data = $response->json('data');
        $this->assertNotEmpty($data['code']);
    }

    public function test_store_validates_organization_service_must_exists_in_database(): void
    {
        $response = $this->postAsUser("/external/facilities/{$this->facility->id}/orders",);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['organization_service_id']
            ])
            ->assertJsonValidationErrors(['organization_service_id']);
    }

    public function test_store_returns_404_for_non_existent_facility(): void
    {
        $response = $this->postAsUser(
            "/external/facilities/99999/orders",
            data: ['organization_service_id' => $this->organizationService->id]
        );

        $response->assertNotFound();
    }

    public function test_store_prevents_duplicate_order_when_new_order_exists(): void
    {
        $this->createOrders();

        $response = $this->postAsUser(
            "/external/facilities/{$this->facility->id}/orders",
            data: ['organization_service_id' => $this->organizationService->id]
        );

        $response->assertStatus(422)
            ->assertJson([
                'flag' => false,
                'message' => 'An active order already exists for this service.',
            ]);

        $this->assertCount(1, Order::all());
    }

    public function test_store_prevents_duplicate_order_when_processing_order_exists(): void
    {
        $this->createOrders(statusId: Status::PROCESSING_ORDER);

        $response = $this->postAsUser(
            "/external/facilities/{$this->facility->id}/orders",
            data: ['organization_service_id' => $this->organizationService->id]
        );

        $response->assertStatus(422)
            ->assertJson([
                'flag' => false,
                'message' => 'An active order already exists for this service.',
            ]);
    }

    public function test_store_prevents_duplicate_order_when_confirmed_order_exists(): void
    {
        $this->createOrders(statusId: Status::CONFIRMED_ORDER);

        $response = $this->postAsUser(
            "/external/facilities/{$this->facility->id}/orders",
            data: ['organization_service_id' => $this->organizationService->id]
        );

        $response->assertStatus(422)
            ->assertJson([
                'flag' => false,
                'message' => 'An active order already exists for this service.',
            ]);
    }

    public function test_store_prevents_duplicate_order_when_approved_order_exists(): void
    {
        $this->createOrders(statusId: Status::APPROVED_ORDER);

        $response = $this->postAsUser(
            "/external/facilities/{$this->facility->id}/orders",
            data: ['organization_service_id' => $this->organizationService->id]
        );

        $response->assertStatus(422)
            ->assertJson([
                'flag' => false,
                'message' => 'An active order already exists for this service.',
            ]);
    }

    public function test_store_prevents_duplicate_order_when_accepted_order_exists(): void
    {
        $this->createOrders(statusId: Status::ACCEPTED_ORDER);

        $response = $this->postAsUser(
            "/external/facilities/{$this->facility->id}/orders",
            data: ['organization_service_id' => $this->organizationService->id]
        );

        $response->assertStatus(422)
            ->assertJson([
                'flag' => false,
                'message' => 'An active order already exists for this service.',
            ]);
    }

    public function test_store_allows_creation_when_previous_order_is_rejected(): void
    {
        $this->createOrders(statusId: Status::REJECTED_ORDER);

        $response = $this->postAsUser(
            "/external/facilities/{$this->facility->id}/orders",
            data: ['organization_service_id' => $this->organizationService->id]
        );

        $response->assertOk()
            ->assertJson([
                'flag' => true,
                'message' => 'Order created successfully.'
            ]);

        $this->assertCount(2, Order::all());
    }

    public function test_store_allows_creation_when_previous_order_is_cancelled(): void
    {
        $this->createOrders(statusId: Status::CANCELED_ORDER);

        $response = $this->postAsUser(
            "/external/facilities/{$this->facility->id}/orders",
            data: ['organization_service_id' => $this->organizationService->id]
        );

        $response->assertOk()
            ->assertJson([
                'flag' => true,
                'message' => 'Order created successfully.'
            ]);

        $this->assertCount(2, Order::all());
    }

    public function test_store_allows_duplicate_orders_for_different_organization_services(): void
    {
        $differentService = OrganizationService::factory()->create();

        $this->createOrders(organizationServiceId: $differentService->id);

        $response = $this->postAsUser(
            "/external/facilities/{$this->facility->id}/orders",
            data: ['organization_service_id' => $this->organizationService->id]
        );

        $response->assertOk();
        $this->assertCount(2, Order::all());
    }

    public function test_stoer_allows_duplicated_orders_for_different_facilities(): void
    {
        $differentFacility = Facility::factory()->create();

        $this->createOrders(facilityId: $differentFacility->id);

        $response = $this->postAsUser(
            "/external/facilities/{$this->facility->id}/orders",
            data: ['organization_service_id' => $this->organizationService->id]
        );

        $response->assertOk();
        $this->assertCount(2, Order::all());
    }

    // ============================================================
    // CANCEL ORDER ENDPOINT
    // ============================================================

    /** @test */
    public function test_cancel_cancels_order_successfully_when_status_is_new(): void
    {
        $order = $this->createOrders();

        $response = $this->patchAsUser("/external/facilities/{$this->facility->id}/orders/{$order->id}/cancel");

        $response->assertOk()
            ->assertJsonStructure([
                'flag',
                'status',
                'message',
                'data' => ['id', 'code', 'user_name', 'organization', 'service', 'status', 'facility', 'updated_at', 'created_at',]
            ])
            ->assertJson([
                'flag' => true,
                'status' => 'success',
                'message' => 'Order cancelled successfully.',
                'data' => [
                    'id' => $order->id,
                    'status' => $this->cancelledStatus->name,
                ]
            ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status_id' => Status::CANCELED_ORDER,
        ]);
    }

    public function test_cancels_returns_updated_order_resource_after_cancellation(): void
    {
        $order = $this->createOrders();

        $response = $this->patchAsUser("/external/facilities/{$this->facility->id}/orders/{$order->id}/cancel");

        $data = $response->json('data');

        $this->assertEquals($order->id, $data['id']);
        $this->assertEquals($this->cancelledStatus->name, $data['status']);
        $this->assertEquals($this->user->name, $data['user_name']);
        $this->assertEquals($this->organizationService->organization->name, $data['organization']);
        $this->assertEquals($this->organizationService->service->name, $data['service']);
    }

    public static function invalidStatusesForCancellationProvider(): array
    {
        return [
            'Processing' => [Status::PROCESSING_ORDER],
            'Confirmed'  => [Status::CONFIRMED_ORDER],
            'Approved'   => [Status::APPROVED_ORDER],
            'Accepted'   => [Status::ACCEPTED_ORDER],
            'Rejected'   => [Status::REJECTED_ORDER],
        ];
    }

    /**
     * @dataProvider invalidStatusesForCancellationProvider
     */
    public function test_cancel_prevents_cancellation_for_invalid_statuses(int $statusId): void
    {
        $order = $this->createOrders(statusId: $statusId);

        $response = $this->patchAsUser("/external/facilities/{$this->facility->id}/orders/{$order->id}/cancel");

        $response->assertStatus(422)
            ->assertJson([
                'flag' => false,
                'message' => 'Only new orders can be cancelled.',
            ]);

        $order->refresh();
        $this->assertEquals($statusId, $order->status_id);
    }

    public function test_cancel_returns_404_for_non_existent_order(): void
    {
        $response = $this->patchAsUser("/external/facilities/{$this->facility->id}/orders/99999/cancel");

        $response->assertNotFound();
    }

    public function test_cancel_returns_404_for_non_existent_facility(): void
    {
        $order = $this->createOrders();

        $response = $this->patchAsUser("/external/facilities/99999/orders/{$order->id}/cancel");

        $response->assertNotFound();
    }

    public function test_cancel_returns_404_when_order_does_not_belong_to_facility(): void
    {
        $differentFacility = Facility::factory()->create();
        $order = $this->createOrders(facilityId: $differentFacility->id);

        $response = $this->patchAsUser("/external/facilities/{$this->facility->id}/orders/{$order->id}/cancel");

        $response->assertNotFound();
    }

    public function test_cancelled_order_allows_creating_new_order_for_same_service(): void
    {
        $order = $this->createOrders();

        $this->patchAsUser("/external/facilities/{$this->facility->id}/orders/{$order->id}/cancel");

        $response = $this->postAsUser(
            "/external/facilities/{$this->facility->id}/orders",
            data: ['organization_service_id' => $this->organizationService->id]
        );

        $response->assertOk()
            ->assertJson(['message' => 'Order created successfully.']);

        // Verify two orders exist (one cancelled, one new)
        $this->assertCount(2, Order::all());
    }

}
