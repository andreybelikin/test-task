<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ValidateRequestParams
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $request->validate([
                'city' => 'required',
                'units' => 'required'
            ]);
        }

        catch (ValidationException $e) {
            $error = ['message' => $e->validator->errors()];
            return response()->json($error, 404);
        }

        return $next($request);
    }
}
