<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class JsonResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        if ($response->getStatusCode() !== null) {
            $flag = $response->getStatusCode() >= 200 && $response->getStatusCode() <= 215;
            $data = json_decode($response->getContent(), true);
            if (!$data) {
                return $response;
            }
            if (!$flag ) {
                // $data['errors'] = array_combine($validator->errors()->keys(), $validator->errors()->all());
                $data = array_merge(['flag' => $flag], ['general_error_message'=> trans('translation.please_contact_customer_service')], $data);
            }
            $data = array_merge(['flag' => $flag], $data);
            $response->setContent(json_encode($data));
        }
        return $response;
    }
}
