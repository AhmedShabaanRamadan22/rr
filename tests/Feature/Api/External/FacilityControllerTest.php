<?php

namespace Feature\Api\External;

use App\Models\AttachmentLabel;
use App\Models\Bank;
use App\Models\City;
use App\Models\District;
use App\Models\Facility;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Tests\Traits\ExternalApiRequests;

class FacilityControllerTest extends TestCase
{
    use RefreshDatabase, ExternalApiRequests;

    protected User $anotherUser;
    protected City $city;
    protected District $district;
    protected AttachmentLabel $requiredLabel;
    protected AttachmentLabel $optionalLabel;

    protected function setUp(): void
    {
        $this->setUpExternalApi();

        $this->anotherUser = User::factory()->create();
        $this->city = City::factory()->create();
        $this->district = District::factory()->create(['city_id' => $this->city->id]);

        $this->requiredLabel = AttachmentLabel::factory()->create([
            'type' => 'facilities',
            'is_required' => '1',
            'placeholder_en' => "Facility's Owner ID",
            'placeholder_ar' => 'هوية مالك المنشأة',
            'extensions' => ['pdf', 'jpg', 'png']
        ]);

        $this->optionalLabel = AttachmentLabel::factory()->create([
            'type' => 'facilities',
            'is_required' => '0',
            'placeholder_en' => 'Additional Document',
            'placeholder_ar' => 'مستند إضافي',
            'extensions' => ['pdf']
        ]);
    }

    protected function createFacilities(int $count = 1, ?int $userId = null): Model | Collection
    {
        $facilities = Facility::factory()
            ->count($count)
            ->create([
                'user_id' => $userId ?? $this->user->id,
                'city_id' => $this->city->id,
                'district_id' => $this->district->id,
            ]);

        return $count === 1 ? $facilities->first() : $facilities;
    }

    protected function assertFacilityJsonStructure($response): void
    {
        $response->assertJsonStructure([
            'flag',
            'status',
            'message',
            'data' => [
                '*' => ['id', 'registration_number', 'name', 'email', 'phone']
            ],
            'meta' => ['current_page', 'last_page', 'per_page', 'total'],
            'links' => ['first', 'last', 'prev', 'next']
        ])->assertJson([
            'flag' => true,
            'status' => 'success',
            'message' => 'Facilities fetched successfully.',
        ]);
    }

    // ============================================================
    // DATA PROVIDERS
    // ============================================================

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
    // INDEX TESTS (Public Facilities List)
    // ============================================================

    public function test_index_returns_paginated_facilities_with_default_pagination()
    {
        $this->createFacilities(21);

        $response = $this->getWithApiToken('/external/facilities');

        $response->assertOk();
        $this->assertFacilityJsonStructure($response);
        $response->assertJsonCount(20, 'data')
            ->assertJsonPath('meta.total', 21);
    }

    public function test_index_supports_custom_pagination()
    {
        $this->createFacilities(6);

        // Custom perPage
        $response = $this->getWithApiToken('/external/facilities?perPage=3');
        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonPath('meta.per_page', 3)
            ->assertJsonPath('meta.total', 6);

        // Custom page
        $response = $this->getWithApiToken('/external/facilities?perPage=2&page=2');
        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.current_page', 2);
    }

    /**
     * @dataProvider invalidPaginationProvider
     */
    public function test_index_validates_pagination_parameters(string $param, mixed $value)
    {
        $response = $this->getWithApiToken("/external/facilities?{$param}={$value}");

        $response->assertStatus(422)
            ->assertJsonValidationErrors($param);
    }

    public function test_index_returns_empty_array_when_no_facilities_exist()
    {
        $response = $this->getWithApiToken('/external/facilities');

        $response->assertOk()
            ->assertJsonCount(0, 'data')
            ->assertJsonPath('meta.total', 0);
    }

    public function test_index_includes_user_relationship_data()
    {
        $facility = $this->createFacilities();

        $response = $this->getWithApiToken('/external/facilities');

        $response->assertOk();
        $data = $response->json('data.0');

        $this->assertArrayHasKey('email', $data);
        $this->assertArrayHasKey('phone', $data);
        $this->assertEquals($facility->user->email, $data['email']);
        $this->assertStringContainsString($facility->user->phone, $data['phone']);
    }


    // ============================================================
    // INDEX MY FACILITIES TESTS (Public User's Facilities List)
    // ============================================================

    public function test_index_my_facilities_returns_paginated_facilities_with_default_pagination()
    {
        $this->createFacilities(21);

        $response = $this->getAsUser('/external/my/facilities');

        $response->assertOk();
        $this->assertFacilityJsonStructure($response);
        $response->assertJsonCount(20, 'data')
            ->assertJsonPath('meta.total', 21);
    }

    public function test_index_my_facilities_only_returns_authenticated_users_facilities()
    {
        $userFacilities = $this->createFacilities(3);
        $this->createFacilities(5, $this->anotherUser->id);

        $response = $this->getAsUser('/external/my/facilities');

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonPath('meta.total', 3);

        $returnedIds = collect($response->json('data'))->pluck('id')->toArray();
        $expectedIds = $userFacilities->pluck('id')->toArray();

        $this->assertEquals(sort($expectedIds), sort($returnedIds));
    }

    public function test_index_my_facilities_supports_custom_pagination()
    {
        $this->createFacilities(6);

        // Custom perPage
        $response = $this->getAsUser('/external/my/facilities?perPage=5');
        $response->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('meta.per_page', 5);

        // Custom page
        $response = $this->getAsUser('/external/my/facilities?perPage=3&page=2');
        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonPath('meta.current_page', 2);
    }

    /**
     * @dataProvider invalidPaginationProvider
     */
    public function test_index_my_facilities_validates_pagination_parameters(string $param, mixed $value)
    {
        $response = $this->getAsUser("/external/my/facilities?{$param}={$value}");

        $response->assertStatus(422)
            ->assertJsonValidationErrors($param);
    }

    public function test_index_my_facilities_returns_empty_data_when_user_has_no_facilities()
    {
        $this->createFacilities(1, $this->anotherUser->id);

        $response = $this->getAsUser('/external/my/facilities');

        $response->assertOk()
            ->assertJsonCount(0, 'data')
            ->assertJsonPath('meta.total', 0);
    }

    public function test_index_my_facilities_includes_user_relationship_data()
    {
        $facility = $this->createFacilities();

        $response = $this->getAsUser('/external/my/facilities');

        $response->assertOk();
        $data = $response->json('data.0');

        $this->assertArrayHasKey('email', $data);
        $this->assertArrayHasKey('phone', $data);
        $this->assertEquals($facility->user->email, $data['email']);
    }

    public function test_different_users_see_only_their_own_facilities()
    {
        $this->createFacilities(2, $this->user->id);
        $this->createFacilities(3, $this->anotherUser->id);

        // User 1 sees only their facilities
        $response = $this->getAsUser('/external/my/facilities', $this->user);
        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.total', 2);

        // User 2 sees only their facilities
        $response = $this->getAsUser('/external/my/facilities', $this->anotherUser);
        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonPath('meta.total', 3);
    }

    // ============================================================
    // SHOW TESTS (Single Facility)
    // ============================================================

    public function test_show_returns_single_facility_successfully()
    {
        $facility = $this->createFacilities();

        // Create attachment
        $facility->attachments()->create([
            'name' => 'test_document.png',
            'path' => 'users/1/facility_owner_id/test_document.png',
            'user_id' => $this->user->id,
            'attachment_label_id' => $this->requiredLabel->id,
        ]);

        $response = $this->getWithApiToken("/external/facilities/{$facility->id}");

        $response->assertOk()
            ->assertJsonStructure([
                'flag',
                'status',
                'message',
                'data' => ['id', 'registration_number', 'name', 'email', 'phone',
                    'attachmentUrl' => [
                        '*' => ['attachment_id', 'attachment_label_id', 'label_ar', 'label_en', 'value', 'name',]
                    ]
                ]
            ]);
    }

    public function test_show_returns_correct_attachment_data()
    {
        $facility = Facility::factory()->create();

        $attachment = $facility->attachments()->create([
            'name' => 'owner_id.png',
            'path' => 'users/1/facility_owner_id/owner_id.png',
            'user_id' => $this->user->id,
            'attachment_label_id' => $this->requiredLabel->id,
        ]);

        $response = $this->getWithApiToken("/external/facilities/{$facility->id}");

        $attachmentData = $response->json('data.attachmentUrl.0');

        // Assert specific values
        $this->assertEquals($attachment->id, $attachmentData['attachment_id']);
        $this->assertEquals($this->requiredLabel->id, $attachmentData['attachment_label_id']);
        $this->assertEquals('هوية مالك المنشأة', $attachmentData['label_ar']);
        $this->assertEquals("Facility's Owner ID", $attachmentData['label_en']);
        $this->assertEquals('owner_id.png', $attachmentData['name']);
        $this->assertStringContainsString('storage', $attachmentData['value']);
        $this->assertStringContainsString('owner_id.png', $attachmentData['value']);
    }

    public function test_show_returns_multiple_attachments_in_array()
    {
        $facility = Facility::factory()->create();

        // Create multiple attachments with different labels
        $attachment1 = $facility->attachments()->create([
            'name' => 'owner_id.png',
            'path' => 'users/1/facility_owner_id/owner_id.png',
            'user_id' => $this->user->id,
            'attachment_label_id' => $this->requiredLabel->id,
        ]);

        $attachment2 = $facility->attachments()->create([
            'name' => 'additional.pdf',
            'path' => 'users/1/additional/additional.pdf',
            'user_id' => $this->user->id,
            'attachment_label_id' => $this->optionalLabel->id,
        ]);

        $response = $this->getWithApiToken("/external/facilities/{$facility->id}");

        $response->assertOk()
            ->assertJsonCount(2, 'data.attachmentUrl');

        $attachments = $response->json('data.attachmentUrl');

        // Verify both attachments are present
        $attachmentIds = collect($attachments)->pluck('attachment_id')->toArray();
        $this->assertContains($attachment1->id, $attachmentIds);
        $this->assertContains($attachment2->id, $attachmentIds);

        // Verify different labels
        $labelIds = collect($attachments)->pluck('attachment_label_id')->unique()->toArray();
        $this->assertCount(2, $labelIds);
    }

    public function test_show_attachment_url_is_full_public_url()
    {
        $facility = Facility::factory()->create();

        $facility->attachments()->create([
            'name' => 'test.png',
            'path' => 'users/1/test.png',
            'user_id' => $this->user->id,
            'attachment_label_id' => $this->requiredLabel->id,
        ]);

        $response = $this->getWithApiToken("/external/facilities/{$facility->id}");

        $attachmentUrl = $response->json('data.attachmentUrl.0.value');

        // Should be a full URL
        $this->assertStringStartsWith('http', $attachmentUrl);
        $this->assertStringContainsString('storage', $attachmentUrl);

        // Should not be a relative path
        $this->assertStringStartsNotWith('/', $attachmentUrl);
        $this->assertStringStartsNotWith('storage/', $attachmentUrl);
    }

    public function test_show_includes_user_relationship_data()
    {
        $facility = $this->createFacilities();

        $response = $this->getWithApiToken("/external/facilities/{$facility->id}");

        $response->assertOk();
        $data = $response->json('data');

        $this->assertEquals($facility->user->email, $data['email']);
        $this->assertStringContainsString($facility->user->phone, $data['phone']);
    }

    public function test_show_returns_404_for_non_existent_facility()
    {
        $response = $this->getWithApiToken('/external/facilities/99999');

        $response->assertNotFound();
    }

    public function test_show_can_retrieve_any_users_facility()
    {
        $otherUserFacility = $this->createFacilities(1, $this->anotherUser->id);

        $response = $this->getWithApiToken("/external/facilities/{$otherUserFacility->id}");

        $response->assertOk()
            ->assertJsonPath('data.id', $otherUserFacility->id);
    }

    // ============================================================
    // STORE ENDPOINT - ATTACHMENT CREATION TESTS
    // ============================================================

    public function test_store_creates_facility_successfully_with_valid_data()
    {
        $data = $this->getValidFacilityData();

        $response = $this->postAsUser('/external/facilities', data: $data);

        $response->assertOk()
            ->assertJson([
                'flag' => true,
                'status' => 'success',
                'message' => 'Facility created successfully.'
            ]);

        $this->assertDatabaseHas('facilities', [
            'name' => $data['name'],
            'registration_number' => $data['registration_number'],
            'user_id' => $this->user->id,
        ]);
    }

    public function test_store_creates_facility_with_all_optional_fields()
    {
        $data = $this->getValidFacilityData([
            'chefs_number' => 5,
            'kitchen_space' => 200,
            'sub_number' => 10,
        ]);

        $response = $this->postAsUser('/external/facilities', data: $data);

        $response->assertOk();

        $this->assertDatabaseHas('facilities', [
            'name' => $data['name'],
            'chefs_number' => 5,
            'kitchen_space' => 200,
            'sub_number' => 10,
        ]);
    }

    public function test_store_creates_facility_with_iban_information()
    {
        $bank = Bank::factory()->create();
        $data = $this->getValidFacilityData([
            'iban' => 'SA0380000000608010167519',
            'account_name' => 'Test Account',
            'bank_id' => $bank->id,
        ]);

        $response = $this->postAsUser('/external/facilities', data: $data);

        $response->assertOk();

        $facility = Facility::where('name', $data['name'])->first();
        $this->assertNotNull($facility->iban);
        $this->assertEquals('SA0380000000608010167519', $facility->iban->iban);
        $this->assertEquals('Test Account', $facility->iban->account_name);
    }

    public function test_store_validates_required_fields()
    {
        $response = $this->postAsUser('/external/facilities');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'registration_number', 'version_date', 'version_date_hj', 'end_date',
                'end_date_hj', 'registration_source', 'license', 'license_expired', 'license_expired_hj', 'capacity',
                'tax_certificate', 'employee_number', 'street_name', 'street_name', 'district_id', 'city_id',
                'building_number', 'postal_code', 'attachments',
            ]);
    }

    public function test_store_requires_all_required_attachments()
    {
        $data = $this->getValidFacilityData();
        unset($data['attachments']); // Remove attachments

        $response = $this->postAsUser('/external/facilities', data: $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('attachments');
    }

    public function test_store_validates_required_attachment_labels()
    {
        $data = $this->getValidFacilityData();

        // Only include optional label, not required
        $data['attachments'] = [
            $this->optionalLabel->id => UploadedFile::fake()->create('optional.pdf')
        ];

        $response = $this->postAsUser('/external/facilities', data: $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('attachments')
            ->assertJsonFragment([
                'attachments' => ["Attachment for label 'Facility's Owner ID' is required."]
            ]);
    }

    public function test_store_rejects_invalid_attachment_label_ids()
    {
        $data = $this->getValidFacilityData();

        // Add attachment with invalid label ID
        $data['attachments'][99999] = UploadedFile::fake()->create('invalid.pdf');

        $response = $this->postAsUser('/external/facilities', data: $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('attachments')
            ->assertJsonFragment([
                'attachments' => ['Invalid attachment keys: 99999']
            ]);
    }

    public function test_store_validates_attachment_file_extensions()
    {
        $data = $this->getValidFacilityData();

        // Create file with invalid extension
        $data['attachments'][$this->requiredLabel->id] = UploadedFile::fake()->create('document.exe');

        $response = $this->postAsUser('/external/facilities', data: $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors("attachments.{$this->requiredLabel->id}")
            ->assertJsonFragment([
                "attachments.{$this->requiredLabel->id}" => [
                    "Attachment 'Facility's Owner ID' must be one of: pdf, jpg, png"
                ]
            ]);
    }

    public function test_store_does_not_require_optional_attachments()
    {
        $data = $this->getValidFacilityData();

        // Only include required attachment, not optional
        $data['attachments'] = [
            $this->requiredLabel->id => UploadedFile::fake()->create('required.pdf')
        ];

        $response = $this->postAsUser('/external/facilities', data: $data);

        $response->assertOk();

        $facility = Facility::where('name', $data['name'])->first();
        $this->assertCount(1, $facility->attachments);
    }

    // ============================================================
    // HELPER METHODS
    // ============================================================

    protected function getValidFacilityData(array $overrides = []): array
    {
        $attachments[$this->requiredLabel->id] = UploadedFile::fake()->create(
            "required.{$this->requiredLabel->extensions[0]}",
            100
        );

        $attachments[$this->optionalLabel->id] = UploadedFile::fake()->create(
            "optional.{$this->optionalLabel->extensions[0]}",
            100
        );


        return array_merge([
            'name' => 'Test Facility ' . uniqid(),
            'registration_number' => rand(1000000000, 9999999999),
            'version_date' => '2024-01-01',
            'version_date_hj' => '1445-05-20',
            'end_date' => '2025-01-01',
            'end_date_hj' => '1446-05-20',
            'registration_source' => $this->city->id,
            'license' => rand(100000000, 999999999),
            'license_expired' => '2025-12-31',
            'license_expired_hj' => '1446-12-30',
            'capacity' => 100,
            'tax_certificate' => rand(100000000, 999999999),
            'employee_number' => 50,
            'street_name' => 'Test Street',
            'district_id' => $this->district->id,
            'city_id' => $this->city->id,
            'building_number' => 123,
            'postal_code' => 12345,
            'attachments' => $attachments,
        ], $overrides);
    }

    // ============================================================
    // VALIDATION TESTS
    // ============================================================

    public static function uniqueFieldsProvider(): array
    {
        return [
            'name' => ['name', 'Test Facility'],
            'registration_number' => ['registration_number', 1234567890],
            'license' => ['license', 987654321],
            'tax_certificate' => ['tax_certificate', 112233445],
        ];
    }

    /**
     * @dataProvider uniqueFieldsProvider
     * @test
     */
    public function store_validates_unique_constraints(string $field, mixed $value)
    {
        // Create existing facility with the field value
        $existing = $this->createFacilities();
        $existing->update([$field => $value]);

        $data = $this->getValidFacilityData([$field => $value]);

        $response = $this->postAsUser('/external/facilities', data: $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors($field);
    }

    public function test_store_validates_date_formats()
    {
        $data = $this->getValidFacilityData([
            'version_date' => '01-01-2024', // Wrong format
            'end_date' => '2024/12/31', // Wrong format
        ]);

        $response = $this->postAsUser('/external/facilities', data: $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['version_date', 'end_date']);
    }

    public function test_store_validates_capacity_minimum_value()
    {
        $data = $this->getValidFacilityData(['capacity' => 0]);

        $response = $this->postAsUser('/external/facilities', data: $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('capacity');
    }

    public function test_store_validates_iban_format()
    {
        $data = $this->getValidFacilityData([
            'iban' => 'INVALID_IBAN',
            'account_name' => 'Test Account',
            'bank_id' => Bank::factory()->create()->id,
        ]);

        $response = $this->postAsUser('/external/facilities', data: $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('iban');
    }

    public function test_store_requires_account_name_and_bank_when_iban_provided()
    {
        $data = $this->getValidFacilityData([
            'iban' => 'SA0380000000608010167519',
        ]);

        $response = $this->postAsUser('/external/facilities', data: $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['account_name', 'bank_id']);
    }

    public function test_store_validates_foreign_key_constraints()
    {
        $data = $this->getValidFacilityData([
            'city_id' => 99999,
            'district_id' => 99999,
        ]);

        $response = $this->postAsUser('/external/facilities', data: $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['city_id', 'district_id']);
    }

}
