<?php
declare(strict_types=1);

namespace gorerider\PrometheusExporter\Providers;

use Prometheus\Storage\APC;
use Prometheus\Storage\Redis;
use Prometheus\Storage\Adapter;
use Prometheus\Storage\InMemory;
use Prometheus\CollectorRegistry;
use Illuminate\Support\ServiceProvider;

abstract class AbstractServiceProvider extends ServiceProvider
{
    protected function registerCollectorRegistry(Adapter $adapter): void
    {
        $this->app->singleton(
            CollectorRegistry::class,
            function ($app) use ($adapter) {
                return new CollectorRegistry($adapter);
            }
        );
    }

    protected function getAdapter(string $adapterConfig): ?Adapter
    {
        $adapter = null;

        if ('memory' === $adapterConfig) {
            $adapter = $this->getMemoryAdapter();
        }

        if (in_array($adapterConfig, ['apc', 'apcu'])) {
            $adapter = $this->getApcAdapter();
        }

        if ('redis' === $adapterConfig) {
            $adapter = $this->getRedisAdapter();
        }

        if (null === $adapter) {
            throw new \InvalidArgumentException(
                'Invalid adapter "' . $adapterConfig . '" configured. Choose one of: memory, apc, redis'
            );
        }

        return $adapter;
    }

    protected function getMemoryAdapter(): InMemory
    {
        return new InMemory();
    }

    protected function getApcAdapter(): APC
    {
        if (false === extension_loaded('apcu')) {
            throw new \ErrorException('Extension "APCU" not loaded. You\'ll have to install it first');
        }

        return new APC();
    }

    protected function getRedisAdapter(): Redis
    {
        return new Redis($this->getConfig('redis'));
    }

    protected function getConfig(string $key)
    {
        return config('prometheus_exporter.'.$key);
    }
}
