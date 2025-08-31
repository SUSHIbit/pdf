<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class RateLimitUploads
{
    public function handle(Request $request, Closure $next): Response
    {
        $userId = auth()->id();
        $key = "upload_limit_{$userId}";
        $maxUploads = 5; // Max uploads per hour
        $timeWindow = 3600; // 1 hour in seconds

        $attempts = Cache::get($key, 0);

        if ($attempts >= $maxUploads) {
            return back()->with('error', 'Upload limit reached. Please try again later.');
        }

        Cache::put($key, $attempts + 1, $timeWindow);

        return $next($request);
    }
}