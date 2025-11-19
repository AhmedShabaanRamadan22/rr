<?php

namespace Tests\Traits;

use App\Models\ApiClient;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

trait ExternalApiRequests
{
    protected string $apiToken = 'test-token';
    protected string $invalidApiToken = 'invalid-token';
    protected array $allowedIps = ['127.0.0.1'];
    protected ApiClient $apiClient;
    protected User $user;

    protected function setUpExternalApi(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->apiClient = ApiClient::create([
            'name' => 'test',
            'token' => $this->apiToken,
            'allowed_ips' => $this->allowedIps,
        ]);
        config(['api.external_token' => $this->apiToken]);

        $this->user = User::factory()->create();
    }

    // Helper method for API token requests
    protected function getWithApiToken(string $uri, array $headers = [])
    {
        return $this->getJson($uri, array_merge([
            'X-API-Token' => $this->apiToken,
        ], $headers));
    }

    protected function postWithApiToken(string $uri, array $data = [], array $headers = [])
    {
        return $this->postJson($uri, $data, array_merge([
            'X-API-Token' => $this->apiToken,
        ], $headers));
    }

    // Helper method for invalid API token requests
    protected function getWithInvalidApiToken(string $uri, array $headers = [])
    {
        return $this->getJson($uri, array_merge([
            'X-API-Token' => $this->invalidApiToken,
        ], $headers));
    }

    protected function postWithInvalidApiToken(string $uri, array $data = [], array $headers = [])
    {
        return $this->postJson($uri, $data, array_merge([
            'X-API-Token' => $this->invalidApiToken,
        ], $headers));
    }

    // Helper method for authenticated user requests (API token + Bearer)
    protected function getAsUser(string $uri, ?User $user = null, array $headers = [])
    {
        $user = $user ?? $this->user ?? User::factory()->create();;

        return $this->actingAs($user, 'sanctum')
            ->getJson($uri, array_merge([
                'X-API-Token' => $this->apiToken,
            ], $headers));
    }

    protected function postAsUser(string $uri, ?User $user = null, array $data = [], array $headers = [])
    {
        $user = $user ?? $this->user ?? User::factory()->create();;

        return $this->actingAs($user, 'sanctum')
            ->postJson($uri, $data, array_merge([
                'X-API-Token' => $this->apiToken,
            ], $headers));
    }

    protected function patchAsUser(string $uri, ?User $user = null, array $data = [], array $headers = [])
    {
        $user = $user ?? $this->user ?? User::factory()->create();;

        return $this->actingAs($user, 'sanctum')
            ->patchJson($uri, $data, array_merge([
                'X-API-Token' => $this->apiToken,
            ], $headers));
    }
}
