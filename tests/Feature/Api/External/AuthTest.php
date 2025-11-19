<?php

namespace Feature\Api\External;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\ExternalApiRequests;

class AuthTest extends TestCase
{
    use RefreshDatabase, ExternalApiRequests;

    protected function setUp(): void
    {
        $this->setUpExternalApi();
    }

    // ============================================================
    // SYSTEM TOKEN (X-API-Token) TESTS
    // ============================================================
    public function test_public_endpoints_require_valid_system_token()
    {
        $response = $this->getJson('/external/facilities');

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthorized: Invalid API token']);
    }

    public function test_public_endpoints_reject_invalid_system_token()
    {
        $response = $this->getWithInvalidApiToken('/external/facilities');

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthorized: Invalid API token']);
    }

    public function test_public_endpoints_reject_inactive_system_token()
    {
        $this->apiClient->update(['active' => false]);

        $response = $this->getWithApiToken('/external/facilities');

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthorized: Invalid API token']);
    }

    public function test_public_endpoints_reject_requests_from_disallowed_ips()
    {
        $this->apiClient->update(['allowed_ips' => ['192.168.1.1']]);

        $response = $this->getWithApiToken('/external/facilities');

        $response->assertStatus(403)
            ->assertJson(['message' => 'Forbidden: IP not allowed']);
    }


    // ============================================================
    // AUTHENTICATED ENDPOINTS (System Token + Sanctum) TESTS
    // ============================================================

    public function test_authenticated_endpoints_require_both_system_token_and_sanctum_auth()
    {
        // No tokens
        $response = $this->getJson('/external/my/facilities');
        $response->assertStatus(401);

        // Only system token
        $response = $this->getWithApiToken('/external/my/facilities');
        $response->assertStatus(401)
            ->assertJson(['message' => trans('translation.Unauthenticated')]);

        // Only Sanctum (no system token)
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/external/my/facilities');
        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthorized: Invalid API token']);

        // Both tokens - success
        $response = $this->getAsUser('/external/my/facilities');
        $response->assertStatus(200);
    }

    public function test_authenticated_endpoints_reject_invalid_system_token_even_with_sanctum()
    {
        $response = $this->withHeader('X-API-Token', 'invalid-token')
            ->actingAs($this->user, 'sanctum')
            ->getJson('/external/my/facilities');

        $response->assertStatus(401);
    }

    public function test_authenticated_endpoints_reject_inactive_client_even_with_sanctum()
    {
        $this->apiClient->update(['active' => false]);

        $response = $this->getAsUser('/external/my/facilities');

        $response->assertStatus(401);
    }
}
