<?php

use gorerider\PrometheusExporter\Instrumentation\FPM;
use gorerider\PrometheusExporter\Instrumentation\Opcache;

return [
    'adapter' => env('PROMETHEUS_EXPORTER_ADAPTER', 'memory'),
    'active_collectibles' => [
        FPM::class,
        Opcache::class,
    ],
    'namespace' => env('PROMETHEUS_EXPORTER_NAMESPACE', env('APP_NAME')),
    'namespace_http_server' => env('PROMETHEUS_EXPORTER_NAMESPACE_HTTP_SERVER', 'http'),
    'buckets' => [50, 100, 300, 500, 700, 900, 1000, 1200, 1500, 2000, 3000, 5000, 7500],
    'redis' => [
        'host' => env('PROMETHEUS_EXPORTER_REDIS_HOST', '127.0.0.1'),
        'port' => env('PROMETHEUS_EXPORTER_REDIS_PORT', 6379),
        'timeout' => env('PROMETHEUS_EXPORTER_REDIS_TIMEOUT', 0.1),
        'read_timeout' => env('PROMETHEUS_EXPORTER_REDIS_READ_TIMEOUT', 0.1),
        'persistent_connections' => env('PROMETHEUS_EXPORTER_REDIS_PERSISTENT_CONN', false),
    ],
    'register_global_middleware' => env('PROMETHEUS_EXPORTER_REGISTER_GLOBAL_MIDDLEWARE', true),
    'opcache_metrics_namespace' => env('PROMETHEUS_EXPORTER_OPCACHE_METRICS_NAMESPACE', 'opcache'),
    'fpm_metrics_namespace' => env('PROMETHEUS_EXPORTER_FPM_METRICS_NAMESPACE', 'fpm'),
];
