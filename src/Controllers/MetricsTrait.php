<?php
declare(strict_types=1);

namespace gorerider\PrometheusExporter\Controllers;

use Prometheus\RenderTextFormat;
use Prometheus\Storage\InMemory;
use Prometheus\CollectorRegistry;
use gorerider\PrometheusExporter\Instrumentation\Collectible;

trait MetricsTrait
{
    public function metrics()
    {
        $renderer = new RenderTextFormat();

        /** @var CollectorRegistry $registry */
        $registry = app(CollectorRegistry::class);

        $metrics = $registry->getMetricFamilySamples();
        $collectibleMetrics = $this->getCollectibleMetrics();

        if (is_array($collectibleMetrics)) {
            $metrics = array_merge($metrics, $collectibleMetrics);
        }

        return response($renderer->render($metrics))
            ->header('Content-Type', RenderTextFormat::MIME_TYPE);
    }

    protected function getCollectibleMetrics(): ?array
    {
        /** @var array $collectibles */
        $collectibles = config('prometheus_exporter.active_collectibles');
        if (!is_array($collectibles)) {
            return null;
        }

        $volatileRegistry = new CollectorRegistry(new InMemory());
        foreach ($collectibles as $collectibleClass) {
            if (!class_exists($collectibleClass)) {
                continue;
            }

            $collectible = new $collectibleClass($volatileRegistry);
            if (!$collectible instanceof Collectible) {
                throw new \RuntimeException($collectibleClass.' does not implement Collectible');
            }

            $collectible->collect();
        }

        return $volatileRegistry->getMetricFamilySamples();
    }
}
