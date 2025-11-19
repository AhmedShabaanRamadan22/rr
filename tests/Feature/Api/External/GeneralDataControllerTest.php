<?php

namespace Feature\Api\External;

use App\Models\AttachmentLabel;
use App\Models\Bank;
use App\Models\City;
use App\Models\Country;
use App\Models\District;
use App\Models\FacilityEmployeePosition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;
use Tests\Traits\ExternalApiRequests;

class GeneralDataControllerTest extends TestCase
{
    use RefreshDatabase, ExternalApiRequests;

    protected static string $baseUrl = '/external/general/';
    protected function setUp(): void
    {
        $this->setUpExternalApi();
        Cache::flush();
    }

    /**
     * Data provider for simple list endpoints (no route parameters)
     */
    public static function simpleListEndpointsProvider(): array
    {
        return [
            'cities' => [
                'endpoint' => self::$baseUrl . 'cities',
                'model' => City::class,
                'factoryData' => ['name_ar' => 'الرياض', 'name_en' => 'Riyadh'],
                'message' => 'Cities retrieved successfully.',
                'expectedKeys' => ['id', 'name']
            ],
            'countries' => [
                'endpoint' => self::$baseUrl . 'countries',
                'model' => Country::class,
                'factoryData' => ['name_ar' => 'السعودية', 'name_en' => 'Saudi Arabia', 'iso3' => 'SA', 'phone_code' => '+966', 'continent' => 'AS'],
                'message' => 'Countries retrieved successfully.',
                'expectedKeys' => ['id', 'name', 'continent']
            ],
            'banks' => [
                'endpoint' => self::$baseUrl . 'banks',
                'model' => Bank::class,
                'factoryData' => ['name_en' => 'Rajhi', 'name_ar' => 'الراجحي'],
                'message' => 'Banks retrieved successfully.',
                'expectedKeys' => ['id', 'name']
            ],
            'facility-employee-positions' => [
                'endpoint' => self::$baseUrl . 'facility-employee-positions',
                'model' => FacilityEmployeePosition::class,
                'factoryData' => ['name_en' => 'Cook', 'name_ar' => 'طباخ'],
                'message' => 'Positions retrieved successfully.',
                'expectedKeys' => ['id', 'name']
            ],
            'attachment-labels' => [
                'endpoint' => self::$baseUrl . 'attachment-labels?type=facilities',
                'model' => AttachmentLabel::class,
                'factoryData' => ['type' => 'facilities', 'is_required' => '1', 'placeholder_en' => "Facility's Owner ID", 'placeholder_ar' => 'هوية مالك المنشأة', 'extensions' => ['pdf', 'jpg', 'png']],
                'message' => 'Attachment labels retrieved successfully.',
                'expectedKeys' => ['id', 'label', 'placeholder', 'is_required', 'extensions'],
            ],
        ];
    }


    // ============================================================
    // GENERAL ENDPOINTS (With no parameters)
    // ============================================================

    /**
     * @dataProvider simpleListEndpointsProvider
     */
    public function test_general_endpoints_return_list_of_items(
        string $endpoint,
        string $model,
        array $factoryData,
        string $message,
        array $expectedKeys
    ): void {
        $model::factory()->count(2)->create($factoryData);

        $response = $this->getWithApiToken($endpoint);

        $response->assertOk()
            ->assertJsonStructure([
                'flag',
                'status',
                'message',
                'data' => ['*' => $expectedKeys]
            ])
            ->assertJsonCount(2, 'data')
            ->assertJson([
                'flag' => true,
                'status' => 'success',
                'message' => $message
            ]);
    }

    /**
     * @test
     * @dataProvider simpleListEndpointsProvider
     */
    public function test_general_endpoints_cache_data(
        string $endpoint,
        string $model,
        array $factoryData,
        string $message,
        array $expectedKeys
    ): void {
        $model::factory()->count(2)->create($factoryData);

        $this->getWithApiToken($endpoint);

        // Delete all records from database
        $model::query()->delete();

        // Second call should return cached data
        $response = $this->getWithApiToken($endpoint);

        // Assert - Still returns 2 items from cache
        $response->assertOk()->assertJsonCount(2, 'data');
    }

    /**
     * @dataProvider simpleListEndpointsProvider
     */
    public function test_it_returns_empty_array_when_no_items_exist(
        string $endpoint,
        string $model,
        array $factoryData,
        string $message,
        array $expectedKeys
    ): void {
        $response = $this->getWithApiToken($endpoint);

        $response->assertOk()
            ->assertJson([
                'flag' => true,
                'status' => 'success',
                'data' => []
            ]);
    }

    // ========================================
    // Nested/Parameterized Endpoints Tests
    // ========================================

    public function test_attachment_labels_returns_empty_array_when_no_labels_match_type(): void
    {
        AttachmentLabel::factory()->count(2)->create([
            'type' => 'facilities',
            'is_required' => '1',
            'extensions' => ['pdf', 'jpg', 'png']
        ]);

        $response = $this->getWithApiToken(self::$baseUrl . 'attachment-labels?type=users');

        $response->assertOk()
            ->assertJson([
                'flag' => true,
                'status' => 'success',
                'data' => []
            ]);
    }

    public function test_attachment_labels__caches_attachment_labels_separately_per_type(): void
    {
        AttachmentLabel::factory()->count(2)->create([
            'type' => 'users',
            'is_required' => '1',
            'extensions' => ['pdf', 'jpg', 'png']
        ]);
        AttachmentLabel::factory()->count(1)->create([
            'type' => 'facilities',
            'is_required' => '1',
            'extensions' => ['pdf', 'jpg', 'png']
        ]);

        $response1 = $this->getWithApiToken(self::$baseUrl . 'attachment-labels?type=users');
        $response1->assertJsonCount(2, 'data');

        AttachmentLabel::query()->delete();

        $response2 = $this->getWithApiToken(self::$baseUrl . 'attachment-labels?type=users');
        $response2->assertOk()->assertJsonCount(2, 'data');

        $response3 = $this->getWithApiToken(self::$baseUrl . 'attachment-labels?type=facilities');
        $response3->assertOk()->assertJsonCount(0, 'data');
    }

    public function test_attachment_labels_validates_type_parameter_must_be_valid_value(): void
    {
        $response = $this->getWithApiToken(self::$baseUrl . 'attachment-labels?type=invalid_type');

        $response->assertStatus(422)->assertJsonValidationErrors(['type']);
    }

    public function test_districts_return_districts_for_a_specific_city(): void
    {
        $city = City::factory()->create();
        $districts = District::factory()->count(3)->create(['city_id' => $city->id,]);

        $response = $this->getWithApiToken(self::$baseUrl . "cities/{$city->id}/districts");

        $response->assertOk()
            ->assertJsonStructure([
                'flag',
                'status',
                'message',
                'data' => ['*' => ['id', 'name']]
            ])
            ->assertJsonCount(3, 'data')
            ->assertJson([
                'flag' => true,
                'status' => 'success',
                'message' => 'Districts retrieved successfully.'
            ]);

        $response->assertJsonFragment([
            'id' => $districts->first()->id,
            'name' => $districts->first()->name,
        ]);
    }

    public function test_districts_returns_404_when_city_does_not_exist(): void
    {
        $response = $this->getWithApiToken(self::$baseUrl . 'cities/99999/districts');
        $response->assertNotFound();
    }

    public function test_districts_returns_empty_array_when_city_has_no_districts(): void
    {
        $city = City::factory()->create();

        $response = $this->getWithApiToken(self::$baseUrl . "cities/{$city->id}/districts");

        $response->assertOk()
            ->assertJson([
                "flag" => true,
                "status" => "success",
                "message" => "Districts retrieved successfully.",
                "data" => []
            ]);
    }

    public function test_districts_only_returns_districts_for_specified_city(): void
    {
        $city1 = City::factory()->create();
        $city2 = City::factory()->create();

        District::factory()->count(3)->create(['city_id' => $city1->id]);
        District::factory()->count(2)->create(['city_id' => $city2->id]);

        $response = $this->getWithApiToken(self::$baseUrl . "cities/{$city1->id}/districts");

        $response->assertOk()->assertJsonCount(3, 'data');
    }

    public function test_districts_caches_districts_separately_per_city(): void
    {
        $city1 = City::factory()->create();
        District::factory()->count(3)->create(['city_id' => $city1->id]);

        $city2 = City::factory()->create();
        District::factory()->count(2)->create(['city_id' => $city2->id]);

        $response1 = $this->getWithApiToken(self::$baseUrl . "cities/{$city1->id}/districts");
        $response1->assertJsonCount(3, 'data');

        District::query()->delete();

        $response2 = $this->getWithApiToken(self::$baseUrl . "cities/{$city1->id}/districts");
        $response2->assertOk()->assertJsonCount(3, 'data');

        $response3 = $this->getWithApiToken(self::$baseUrl . "cities/{$city2->id}/districts");
        $response3->assertOk()->assertJsonCount(0, 'data');
    }
}
