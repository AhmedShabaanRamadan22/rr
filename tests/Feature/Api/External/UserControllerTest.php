<?php

namespace Feature\Api\External;

use App\Models\AttachmentLabel;
use App\Models\City;
use App\Models\Facility;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Tests\Traits\ExternalApiRequests;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, ExternalApiRequests;

    protected City $city;
    protected AttachmentLabel $requiredLabel;
    protected AttachmentLabel $optionalLabel;

    protected function setUp(): void
    {
        $this->setUpExternalApi();

        $this->city = City::factory()->create();
    }

    protected function createUsers(int $count = 1, ?int $facilityId = null): Model | Collection
    {
        $users = User::factory()->count($count)->create(['national_source' => $this->city->id]);

        return $count === 1 ? $users->first() : $users;
    }

    protected function createAttachmentLabels()
    {
        $this->requiredLabel = AttachmentLabel::factory()->create([
            'type' => 'users',
            'is_required' => '1',
            'placeholder_en' => "Owner ID",
            'placeholder_ar' => 'هوية المالك',
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
    // INDEX TESTS
    // ============================================================

    public function test_index_returns_paginated_users_with_default_pagination()
    {
        $this->createUsers(21);

        $response = $this->getWithApiToken("/external/users");

        $response->assertOk()
            ->assertJsonStructure([
                'flag',
                'status',
                'message',
                'data' => [
                    '*' => ['id', 'name', 'phone', 'email', 'profile_photo', 'address', 'national_source', 'birthday', 'national_id_expired', 'national_id', 'nationality'],
                ],
                'meta' => ['current_page', 'last_page', 'per_page', 'total'],
                'links' => ['first', 'last', 'prev', 'next']
            ]);
        $response->assertJsonCount(20, 'data')
            ->assertJsonPath('meta.total', 22); // a user is already created in the setup function
    }

    public function test_index_supports_custom_pagination()
    {
        $this->createUsers(6);

        // Custom perPage
        $response = $this->getWithApiToken("/external/users?perPage=5");
        $response->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('meta.per_page', 5);

        // Custom page
        $response = $this->getWithApiToken("/external/users?perPage=3&page=2");
        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonPath('meta.current_page', 2);
    }

    /**
     * @dataProvider invalidPaginationProvider
     */
    public function test_index_validates_pagination_parameters(string $param, mixed $value)
    {
        $response = $this->getWithApiToken("/external/users?{$param}={$value}");

        $response->assertStatus(422)->assertJsonValidationErrors($param);
    }

    public function test_index_returns_empty_data_when_there_are_no_users()
    {
        $this->user->delete();

        $response = $this->getWithApiToken("/external/users");

        $response->assertOk()
            ->assertJsonCount(0, 'data')
            ->assertJsonPath('meta.total', 0);
    }

    public function test_index_includes_national_source_relationship_data()
    {
        $user = $this->createUsers();

        $response = $this->getWithApiToken("/external/users");

        $response->assertOk();
        $data = $response->json('data.1');

        $this->assertArrayHasKey('national_source', $data);
        $this->assertEquals($user->national_source_name, $data['national_source']);
    }

    // ============================================================
    // SHOW TESTS (Single Facility)
    // ============================================================
    public function test_show_returns_single_user_successfully()
    {
        $user = $this->createUsers();
        $this->createAttachmentLabels();

        // Create attachment
        $user->attachments()->create([
            'name' => 'test_document.png',
            'path' => 'users/1/id/test_document.png',
            'user_id' => $this->user->id,
            'attachment_label_id' => $this->requiredLabel->id,
        ]);

        $response = $this->getWithApiToken("/external/users/{$user->id}");

        $response->assertOk()
            ->assertJsonStructure([
                'flag',
                'status',
                'message',
                'data' => ['id', 'name', 'phone', 'email', 'profile_photo', 'address', 'national_source', 'birthday', 'national_id_expired', 'national_id', 'nationality',
                            'attachmentUrl' => [
                                '*' => ['attachment_id', 'attachment_label_id', 'label_ar', 'label_en', 'value', 'name',]
                            ]
                ],
            ]);
    }

    public function test_show_returns_correct_attachment_data()
    {
        $user = $this->createUsers();
        $this->createAttachmentLabels();

        $attachment = $user->attachments()->create([
            'name' => 'owner_id.png',
            'path' => 'users/1/id/owner_id.png',
            'user_id' => $this->user->id,
            'attachment_label_id' => $this->requiredLabel->id,
        ]);

        $response = $this->getWithApiToken("/external/users/{$user->id}");

        $attachmentData = $response->json('data.attachmentUrl.0');

        // Assert specific values
        $this->assertEquals($attachment->id, $attachmentData['attachment_id']);
        $this->assertEquals($this->requiredLabel->id, $attachmentData['attachment_label_id']);
        $this->assertEquals('هوية المالك', $attachmentData['label_ar']);
        $this->assertEquals("Owner ID", $attachmentData['label_en']);
        $this->assertEquals('owner_id.png', $attachmentData['name']);
        $this->assertStringContainsString('storage', $attachmentData['value']);
        $this->assertStringContainsString('owner_id.png', $attachmentData['value']);
    }

    public function test_show_returns_multiple_attachments_in_array()
    {
        $user = $this->createUsers();
        $this->createAttachmentLabels();

        // Create multiple attachments with different labels
        $attachment1 = $user->attachments()->create([
            'name' => 'owner_id.png',
            'path' => 'users/1/id/owner_id.png',
            'user_id' => $this->user->id,
            'attachment_label_id' => $this->requiredLabel->id,
        ]);

        $attachment2 = $user->attachments()->create([
            'name' => 'additional.pdf',
            'path' => 'users/1/additional/additional.pdf',
            'user_id' => $this->user->id,
            'attachment_label_id' => $this->optionalLabel->id,
        ]);

        $response = $this->getWithApiToken("/external/users/{$user->id}");

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
        $user = $this->createUsers();
        $this->createAttachmentLabels();

        $user->attachments()->create([
            'name' => 'test.png',
            'path' => 'users/1/test.png',
            'user_id' => $this->user->id,
            'attachment_label_id' => $this->requiredLabel->id,
        ]);

        $response = $this->getWithApiToken("/external/users/{$user->id}");

        $attachmentUrl = $response->json('data.attachmentUrl.0.value');

        // Should be a full URL
        $this->assertStringStartsWith('http', $attachmentUrl);
        $this->assertStringContainsString('storage', $attachmentUrl);

        // Should not be a relative path
        $this->assertStringStartsNotWith('/', $attachmentUrl);
        $this->assertStringStartsNotWith('storage/', $attachmentUrl);
    }

    public function test_show_includes_national_source_relationship_data()
    {
        $user = $this->createUsers();

        $response = $this->getWithApiToken("/external/users/{$user->id}");

        $response->assertOk();
        $data = $response->json('data');

        $this->assertEquals($user->national_source_name, $data['national_source']);
    }

    public function test_show_returns_404_for_non_existent_user()
    {
        $response = $this->getWithApiToken('/external/users/99999');

        $response->assertNotFound();
    }
}
