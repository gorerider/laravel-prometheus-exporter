<?php
declare(strict_types=1);

namespace gorerider\PrometheusExporter\Providers;

use gorerider\PrometheusExporter\Middleware\PrometheusExporterMiddleware;

class LumenServiceProvider extends AbstractServiceProvider
{
    public function boot()
    {
        $this->app->middleware(['prometheus_exporter' => PrometheusExporterMiddleware::class]);
    }

    public function register()
    {
        $this->configureInstrumentation();

        $this->registerCollectorRegistry(
            $this->getAdapter($this->getConfig('adapter'))
        );
    }

    protected function configureInstrumentation(): void
    {
        $this->app->configure('prometheus_exporter');

        $source = realpath(__DIR__.'/../config/config.php');
        $this->mergeConfigFrom($source, 'prometheus_exporter');
    }
}
