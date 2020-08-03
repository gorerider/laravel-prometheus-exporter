<?php
declare(strict_types=1);

namespace gorerider\PrometheusExporter\Middleware;

use Closure;
use Prometheus\Histogram;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Prometheus\CollectorRegistry;

class PrometheusExporterMiddleware
{
    /** @var CollectorRegistry */
    protected $registry;

    /** @var Histogram */
    protected $requestDurationHistogram;

    public function __construct(CollectorRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        $this->measure($request, $response);
    }

    protected function measure(Request $request, Response $response): void
    {
        $this->initMetrics();

        [
            'route' => $route,
            'statusCode' => $statusCode,
            'durationMs' => $durationMs,
        ] = $this->getRouteDetails($request, $response);

        $this->countRequest($route, (string) $statusCode, $durationMs);
    }

    protected function initMetrics(): void
    {
        $namespace = config('prometheus_exporter.namespace');
        $buckets = config('prometheus_exporter.buckets');
        $labelNames = ['route', 'statusCode'];

        $this->requestDurationHistogram = $this->registry->getOrRegisterHistogram(
            $namespace,
            'request_duration_milliseconds',
            'duration of http requests',
            $labelNames,
            $buckets
        );
    }

    protected function countRequest(string $route, string $statusCode, float $durationMs): void
    {
        $labelValues = [$route, $statusCode];
        $this->requestDurationHistogram->observe($durationMs, $labelValues);
    }

    protected function getRouteDetails(Request $request, Response $response): array
    {
        $start = $request->server('REQUEST_TIME_FLOAT');
        $duration = microtime(true) - $start;

        return [
            'route' => $request->getMethod().' '.$request->getPathInfo(),
            'statusCode' => $response->getStatusCode(),
            'durationMs' => $duration * 1000.0,
        ];
    }
}
