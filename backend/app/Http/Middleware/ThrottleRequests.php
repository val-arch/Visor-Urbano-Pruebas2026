<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ThrottleRequests
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle(Request $request, Closure $next, $limit = 60, $decayMinutes = 1)
    {
        $key = $this->resolveRequestSignature($request);

        if ($this->limiter->tooManyAttempts($key, $limit, $decayMinutes)) {
            // Log the IP address when too many attempts
            Log::warning('Too many attempts from IP: ' . $request->ip());

            return $this->buildResponse($key, $limit);
        }

        $this->limiter->hit($key, $decayMinutes);

        $response = $next($request);

        return $this->addHeaders(
            $response, $limit,
            $this->calculateRemainingAttempts($key, $limit)
        );
    }

    protected function resolveRequestSignature($request)
    {
        return sha1(
            $request->method().
            '|'.$request->server('SERVER_NAME').
            '|'.$request->path().
            '|'.$request->ip()
        );
    }

    protected function buildResponse($key, $maxAttempts)
    {
        $response = new Response('Too Many Attempts.', 429);

        $retryAfter = $this->limiter->availableIn($key);

        return $this->addHeaders(
            $response, $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts, $retryAfter),
            $retryAfter
        );
    }

    protected function addHeaders(Response $response, $maxAttempts, $remainingAttempts, $retryAfter = null)
    {
        $response->headers->add([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => $remainingAttempts,
        ]);

        if ($retryAfter) {
            $response->headers->add([
                'Retry-After' => $retryAfter,
                'X-RateLimit-Reset' => time() + $retryAfter,
            ]);
        }

        return $response;
    }

    protected function calculateRemainingAttempts($key, $maxAttempts, $retryAfter = null)
    {
        if ($retryAfter) {
            return 0;
        }

        return $this->limiter->retriesLeft($key, $maxAttempts);
    }
}
