<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function success($data = null, string $message = 'Success', int $status = 200, array $additional = []): JsonResponse
    {
        $response = [
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ];

        return response()->json(array_merge($response, $additional), $status);
    }

    protected function successPaginated($data = null, $paginator = null, string $message = 'Success', int $status = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'links' => [
                'first' => $paginator->url(1),
                'last' => $paginator->url($paginator->lastPage()),
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl(),
            ],
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ], $status);
    }

    protected function error(string $message = 'Error', int $status = 500, $generalErrorMessage = null): JsonResponse
    {
        return response()->json([
            'flag' => false,
            'message' => $message,
            'general_error_message' => $generalErrorMessage ?? trans('translation.Please contact customer service'),
        ], $status);
    }
}
