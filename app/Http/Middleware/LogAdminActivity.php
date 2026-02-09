<?php

namespace App\Http\Middleware;

use App\Services\ActivityLogger;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class LogAdminActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $request->user()?->is_admin || $request->attributes->get('activity_logged') === true) {
            return $response;
        }

        $route = $request->route();

        if (! $route) {
            return $response;
        }

        $method = strtoupper($request->method());
        $routeName = $route->getName() ?: $request->path();
        $isReadRequest = in_array($method, ['GET', 'HEAD', 'OPTIONS'], true);

        $properties = [
            'route_name' => $route->getName(),
            'path' => $request->path(),
            'method' => $method,
            'status_code' => $response->getStatusCode(),
        ];

        if ($isReadRequest) {
            $properties['query'] = $this->sanitize($request->query());
        } else {
            $properties['payload'] = $this->sanitize(
                $request->except(['_token', 'password', 'password_confirmation', 'current_password'])
            );
        }

        ActivityLogger::log(
            $isReadRequest ? 'admin.page_accessed' : 'admin.action_performed',
            null,
            $isReadRequest
                ? sprintf('Admin membuka halaman %s.', $routeName)
                : sprintf('Admin menjalankan %s pada %s.', $method, $routeName),
            $properties
        );

        return $response;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function sanitize(array $data): array
    {
        $normalized = [];

        foreach ($data as $key => $value) {
            $normalized[$key] = $this->normalizeValue($value);
        }

        return $normalized;
    }

    private function normalizeValue(mixed $value): mixed
    {
        if ($value instanceof UploadedFile) {
            return $value->getClientOriginalName();
        }

        if (is_array($value)) {
            return array_map(fn (mixed $item) => $this->normalizeValue($item), $value);
        }

        if (is_string($value)) {
            return mb_strlen($value) > 500
                ? mb_substr($value, 0, 500) . '...'
                : $value;
        }

        if (is_scalar($value) || $value === null) {
            return $value;
        }

        if (is_object($value) && method_exists($value, '__toString')) {
            return (string) $value;
        }

        return get_debug_type($value);
    }
}
