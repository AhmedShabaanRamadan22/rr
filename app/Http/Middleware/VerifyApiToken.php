<?php

namespace App\Http\Middleware;

use App\Models\ApiClient;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-API-Token');
        $client = ApiClient::token($token)->active()->first();

        // token-based auth
        if (!$client) {
            return response()->json(['message' => 'Unauthorized: Invalid API token'], 401);
        }

        $ip = $request->ip();
        if(!$client->isIpAllowed($ip)) {
            return response()->json(['message' => 'Forbidden: IP not allowed'], 403);
        }

        // Store client in request attributes
        $request->attributes->set('api_client', $client);

        $response = $next($request);

        // Request logging
        try {
            $client = $request->attributes->get('api-client');

            Log::channel('external_api_activity')->info('API Request', [
                'timestamp' => now()->toDateTimeString(),
                'client_id' => $client?->id,
                'client_name' => $client?->name,
                'ip_address' => $request->ip(),
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'request_body' => $request->except(['password', 'token']),
                'response' => json_decode($response->getContent(), true),
                'status_code' => $response->getStatusCode(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to log API activity', [
                'error' => $e->getMessage(),
            ]);
        }

        return $response;
    }
}
