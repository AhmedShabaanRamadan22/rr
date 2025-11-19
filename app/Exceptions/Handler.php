<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Sentry\Laravel\Integration;
use Sentry\State\Scope;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            if (auth()->check()) {
                \Sentry\configureScope(function (Scope $scope) {
                    $user = auth()->user();
                    $scope->setUser([
                        'id'    => $user->id,
                        'email' => $user->email,
                        'name'  => $user->name,
                        'phone'  => $user->phone,
                        // 'roles' => $user->roles,
                    ]);
                });
            }
            Integration::captureUnhandledException($e);
        });
    }


    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {
            // Handle 401 - Unauthenticated
            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'flag' => false,
                    'message' => trans('translation.Unauthenticated'),
                    'general_error_message' => trans('translation.Please contact customer service'),
                ], 401);
            }

            // Handle 404 - Not Found
            if ($e instanceof NotFoundHttpException || $e instanceof ModelNotFoundException) {
                return response()->json([
                    'flag' => false,
                    'message' => trans('translation.Resource not found'),
                    'general_error_message' => trans('translation.Please contact customer service'),
                ], 404);
            }

            // Handle 403 - Unauthorized
            if ($e instanceof AuthorizationException) {
                return response()->json([
                    'flag' => false,
                    'message' => trans('translation.This action is unauthorized.'),
                    'general_error_message' => trans('translation.You do not have permission'),
                ], 403);
            }

            // Handle 429 - Too Many Requests
            if ($e instanceof ThrottleRequestsException) {
                return response()->json([
                    'flag' => false,
                    'message' => trans('translation.Too Many Attempts.'),
                    'general_error_message' => trans('translation.Please try again later'),
                ], 429);
            }

            // Handle 422 - Validation Error
            if ($e instanceof ValidationException) {
                return response()->json([
                    'flag' => false,
                    'message' => trans('translation.Validation failed'),
                    'errors' => $e->errors(),
                    'general_error_message' => trans('translation.Please check your input'),
                ], 422);
            }

            // Handle 500 - Internal Server Error
            $message = app()->environment('production')
                ? trans('translation.Internal server error')
                : $e->getMessage();

            $response = [
                'flag' => false,
                'message' => $message,
                'general_error_message' => trans('translation.Please contact customer service'),
            ];

            // Add debug info in local environment only
            if (app()->environment('local')) {
                $response['exception'] = get_class($e);
                $response['file'] = $e->getFile();
                $response['line'] = $e->getLine();
            }

            return response()->json($response, 500);
        }

        return parent::render($request, $e);
    }
}
