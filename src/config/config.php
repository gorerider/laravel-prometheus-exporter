<?php

return [
    /*
     * possible values are apc or redis
     */
    'adapter' => env('PROMETHEUS_ADAPTER', 'apc'),

    'namespace' => 'app',

    'namespace_http_server' => 'http',

    'histogram_buckets' => [50, 100, 300, 500, 700, 900, 1000, 1200, 1500, 2000, 3000, 5000, 7500],

    'redis' => [
        'host'                   => '127.0.0.1',
        'port'                   => 6379,
        'timeout'                => 0.1,  // in seconds
        'read_timeout'           => 10, // in seconds
        'persistent_connections' => false,
    ],

    'register_global_middleware' => true,

    'export_opcache_statistics' => true,

    'opcache_metrics_namespace' => 'opcache',

    'export_fpm_statistics' => true,

    'fpm_statistics_namespace' => 'fpm',

    'active_collectibles' => [
        \traumferienwohnungen\PrometheusExporter\Instrumentation\FPM::class,
        \traumferienwohnungen\PrometheusExporter\Instrumentation\Opcache::class,
    ]
];
