<?php

namespace App\Services;

use App\Exceptions\CloudflareException;
use App\Http\Resources\CloudflareCustomHostnameResource;
use App\Models\Organization;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CloudflareService
{
    private $BASE_URL = "https://api.cloudflare.com/client/v4/zones/";

    public function getCustomHostnameStatus(Organization $organization)
    {
        $zone_id = config('services.cloudflare.zone_id');
        $response = Http::withToken(config('services.cloudflare.token'))
            ->asJson()
            ->get($this->BASE_URL . $zone_id . "/custom_hostnames");
        if (!$response->successful()) {
            $this->captureError('error in fetching the custom hostname list.', $response);
        }
        foreach ($response->json()['result'] as $cloudflare_custom_hostname) {
            $organization_domain = parse_url($organization->domain)['host'];
            if ($organization_domain === $cloudflare_custom_hostname['hostname']) {
                $organization->update([
                    'cloudflare_custom_hostname_id' => $cloudflare_custom_hostname['id']
                ]);
                return CloudflareCustomHostnameResource::make($cloudflare_custom_hostname);
            }
        }
        return $this->createCustomHostname($organization);
    }

    private function createCustomHostname(Organization $organization)
    {
        $zone_id = config('services.cloudflare.zone_id');
        $response = Http::withToken(config('services.cloudflare.token'))
            ->asJson()
            ->post($this->BASE_URL . $zone_id . "/custom_hostnames", [
                'hostname' => parse_url($organization->domain)['host'],
                'ssl' => [
                    'method' => 'txt',
                    'type' => 'dv', 
                    'settings' => [
                        'http2' => 'on',
                        'min_tls_version' => '1.1'
                    ]
                ]
            ]);
        if (!$response->successful()) {
            $this->captureError('error in creating the custom hostname.', $response);
        }
        $result = $response->json()['result'];
        $organization->update([
            'cloudflare_custom_hostname_id' => $result['id']
        ]);
        return CloudflareCustomHostnameResource::make($result);
    }

    private function captureError($message, $response)
    {
        $exception = new CloudflareException($message, $response->status());

        \Sentry\configureScope(function (\Sentry\State\Scope $scope) use ($response) {
            $scope->setContext('Cloudflare Response', $response->json());
        });
        Log::error($message, $response->json());
        \Sentry\captureException($exception);
        abort(500);
    }
}
