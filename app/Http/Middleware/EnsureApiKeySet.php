<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiKeySet
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(Request): (Response) $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (empty(config('app.api_key'))) {
            return response()->json(['message' => 'Can\'t find API_KEY parameter in .env on the server'], 500);
        }

        return $next($request);
    }
}
