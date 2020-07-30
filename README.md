# laravel-prometheus-exporter

[![Build Status](https://travis-ci.org/gorerider/laravel-prometheus-exporter.svg?branch=master)](https://travis-ci.org/gorerider/laravel-prometheus-exporter)

This is a fork of [traum-ferienwohnungen/laravel-prometheus-exporter](https://github.com/traum-ferienwohnungen/laravel-prometheus-exporter)
with updated dependencies. Main goal of this work is to make the lib compatible with Laravel 7 and Lumen 7 running on PHP 7.4.

## Description
A prometheus exporter for the Laravel and the Lumen web framework.

It tracks latency and request counts by request method, route path and response code.

## Installation
`composer require gorerider/laravel-prometheus-exporter`

### Adapters
Then choose from three storage adapters:
Memory (default), APCu and Redis can also be used.

#### APCu
Ensure apcu-bc is installed and enabled.
If you're using the official PHP docker hub non-alpine image, have a look in the Dockerfile
on how to install.

#### Redis
Ensure php redis is installed and enabled.
If you're using the official PHP docker hub non-alpine image, have a look in the Dockerfile
on how to install.

By default, it looks for a redis server at localhost:6379. The server
can be configured in `config/prometheus_exporter.php`.

### Laravel
If you're requiring this package in Laravel,
middleware and route will be configured for you automatically.

#### Enable the Service Provider
In `config/app.php` (if not already there):

```
'providers' => [
    ...
    /*
     * Package Service Providers...
     */
    gorerider\PrometheusExporter\Providers\LaravelServiceProvider::class,
    ...
]
```

### Lumen
#### Register the ServiceProvider
In `bootstrap/app.php`
```
$app->register(gorerider\PrometheusExporter\Providers\LumenServiceProvider::class);
```

#### Add an endpoint for the metrics
In `bootstrap/app.php`
```
$app->router->get('/metrics', [
    'as' => 'metrics',
    'uses' => 'gorerider\PrometheusExporter\Controllers\LumenMetricsController@metrics',
]);
```

## Configuration
Metrics are exposed on `/metrics` endpoint, e.g. `http://my-service.com/metrics`

The configuration can be found in `config/prometheus_exporter.php`.

All values can be configured via .env or environment variables.

| name        | description                                             | env |
|-------------|---------------------------------------------------------|-----|
| adapter     | Storage adapter to use: 'memory', 'apc' or 'redis' default: 'memory' | PROMETHEUS_EXPORTER_ADAPTER |
| active_collectibles | Additional metric collectibles | n.a. |
| buckets     | HTTP requests latency histogram buckets | n.a. |
| namespace   | Name (prefix) to use in prometheus metrics. default: value in APP_NAME env var | PROMETHEUS_EXPORTER_NAMESPACE |
| redis       | Array of redis parameters | see prometheus_exporter.php for details |
| opcache_metrics_namespace | Name (prefix) to use for OPCache metrics. default: opcache | PROMETHEUS_EXPORTER_OPCACHE_METRICS_NAMESPACE |
| fpm_metrics_namespace | Name (prefix) to use for PHP-FPM metrics. default: fpm | PROMETHEUS_EXPORTER_FPM_METRICS_NAMESPACE |

### Collectibles

If you want to disable any collectibles,
you'll need to remove entries from the `prometheus_exporter.php` config.

#### Laravel

Run

```
php artisan vendor:publish
```

Finally, remove collectibles in `app/config/prometheus_exporter.php`

#### Lumen

Copy `src/config/prometheus_exporter.php` in this repo and add to your Lumen project
in the config directory.

Finally, remove collectibles in `app/config/prometheus_exporter.php`

## Example `/metrics`

```
# HELP fpm_accepted_conn accepted_conn
# TYPE fpm_accepted_conn gauge
fpm_accepted_conn 71
# HELP fpm_active_processes active_processes
# TYPE fpm_active_processes gauge
fpm_active_processes 1
# HELP fpm_idle_processes idle_processes
# TYPE fpm_idle_processes gauge
fpm_idle_processes 2
# HELP fpm_listen_queue listen_queue
# TYPE fpm_listen_queue gauge
fpm_listen_queue 0
# HELP fpm_listen_queue_len listen_queue_len
# TYPE fpm_listen_queue_len gauge
fpm_listen_queue_len 128
# HELP fpm_max_active_processes max_active_processes
# TYPE fpm_max_active_processes gauge
fpm_max_active_processes 3
# HELP fpm_max_children_reached max_children_reached
# TYPE fpm_max_children_reached gauge
fpm_max_children_reached 0
# HELP fpm_max_listen_queue max_listen_queue
# TYPE fpm_max_listen_queue gauge
fpm_max_listen_queue 0
# HELP fpm_proc_requests proc requests
# TYPE fpm_proc_requests gauge
fpm_proc_requests{pid="6"} 24
fpm_proc_requests{pid="7"} 24
fpm_proc_requests{pid="732"} 23
# HELP fpm_proc_start_time proc start time
# TYPE fpm_proc_start_time gauge
fpm_proc_start_time{pid="6"} 1596095160
fpm_proc_start_time{pid="7"} 1596095160
fpm_proc_start_time{pid="732"} 1596097386
# HELP fpm_slow_requests slow_requests
# TYPE fpm_slow_requests gauge
fpm_slow_requests 0
# HELP fpm_start_time start_time
# TYPE fpm_start_time gauge
fpm_start_time 1596095160
# HELP opcache_cache_full 
# TYPE opcache_cache_full gauge
opcache_cache_full 0
# HELP opcache_interned_strings_usage_buffer_size 
# TYPE opcache_interned_strings_usage_buffer_size gauge
opcache_interned_strings_usage_buffer_size 12582464
# HELP opcache_interned_strings_usage_free_memory 
# TYPE opcache_interned_strings_usage_free_memory gauge
opcache_interned_strings_usage_free_memory 11203240
# HELP opcache_interned_strings_usage_number_of_strings 
# TYPE opcache_interned_strings_usage_number_of_strings gauge
opcache_interned_strings_usage_number_of_strings 20693
# HELP opcache_interned_strings_usage_used_memory 
# TYPE opcache_interned_strings_usage_used_memory gauge
opcache_interned_strings_usage_used_memory 1379224
# HELP opcache_memory_usage_free_memory 
# TYPE opcache_memory_usage_free_memory gauge
opcache_memory_usage_free_memory 180941720
# HELP opcache_memory_usage_used_memory 
# TYPE opcache_memory_usage_used_memory gauge
opcache_memory_usage_used_memory 20181624
# HELP opcache_memory_usage_wasted_memory 
# TYPE opcache_memory_usage_wasted_memory gauge
opcache_memory_usage_wasted_memory 203248
# HELP opcache_opcache_enabled 
# TYPE opcache_opcache_enabled gauge
opcache_opcache_enabled 1
# HELP opcache_opcache_statistics_blacklist_misses 
# TYPE opcache_opcache_statistics_blacklist_misses gauge
opcache_opcache_statistics_blacklist_misses 0
# HELP opcache_opcache_statistics_hits 
# TYPE opcache_opcache_statistics_hits gauge
opcache_opcache_statistics_hits 10595
# HELP opcache_opcache_statistics_last_restart_time 
# TYPE opcache_opcache_statistics_last_restart_time gauge
opcache_opcache_statistics_last_restart_time 0
# HELP opcache_opcache_statistics_manual_restarts 
# TYPE opcache_opcache_statistics_manual_restarts gauge
opcache_opcache_statistics_manual_restarts 0
# HELP opcache_opcache_statistics_max_cached_keys 
# TYPE opcache_opcache_statistics_max_cached_keys gauge
opcache_opcache_statistics_max_cached_keys 16229
# HELP opcache_opcache_statistics_misses 
# TYPE opcache_opcache_statistics_misses gauge
opcache_opcache_statistics_misses 215
# HELP opcache_opcache_statistics_num_cached_keys 
# TYPE opcache_opcache_statistics_num_cached_keys gauge
opcache_opcache_statistics_num_cached_keys 394
# HELP opcache_opcache_statistics_num_cached_scripts 
# TYPE opcache_opcache_statistics_num_cached_scripts gauge
opcache_opcache_statistics_num_cached_scripts 202
# HELP opcache_opcache_statistics_oom_restarts 
# TYPE opcache_opcache_statistics_oom_restarts gauge
opcache_opcache_statistics_oom_restarts 0
# HELP opcache_opcache_statistics_start_time 
# TYPE opcache_opcache_statistics_start_time gauge
opcache_opcache_statistics_start_time 1596095160
# HELP opcache_restart_in_progress 
# TYPE opcache_restart_in_progress gauge
opcache_restart_in_progress 0
# HELP opcache_restart_pending 
# TYPE opcache_restart_pending gauge
opcache_restart_pending 0
# HELP search_api_request_duration_milliseconds duration of http requests
# TYPE search_api_request_duration_milliseconds histogram
search_api_request_duration_milliseconds_bucket{route="GET /",statusCode="200",le="50"} 0
search_api_request_duration_milliseconds_bucket{route="GET /",statusCode="200",le="100"} 0
search_api_request_duration_milliseconds_bucket{route="GET /",statusCode="200",le="300"} 3
search_api_request_duration_milliseconds_bucket{route="GET /",statusCode="200",le="500"} 3
search_api_request_duration_milliseconds_bucket{route="GET /",statusCode="200",le="700"} 3
search_api_request_duration_milliseconds_bucket{route="GET /",statusCode="200",le="900"} 3
search_api_request_duration_milliseconds_bucket{route="GET /",statusCode="200",le="1000"} 3
search_api_request_duration_milliseconds_bucket{route="GET /",statusCode="200",le="1200"} 3
search_api_request_duration_milliseconds_bucket{route="GET /",statusCode="200",le="1500"} 3
search_api_request_duration_milliseconds_bucket{route="GET /",statusCode="200",le="2000"} 3
search_api_request_duration_milliseconds_bucket{route="GET /",statusCode="200",le="3000"} 3
search_api_request_duration_milliseconds_bucket{route="GET /",statusCode="200",le="5000"} 3
search_api_request_duration_milliseconds_bucket{route="GET /",statusCode="200",le="7500"} 3
search_api_request_duration_milliseconds_bucket{route="GET /",statusCode="200",le="+Inf"} 3
search_api_request_duration_milliseconds_count{route="GET /",statusCode="200"} 3
search_api_request_duration_milliseconds_sum{route="GET /",statusCode="200"} 657.34791755676
search_api_request_duration_milliseconds_bucket{route="GET /asdasd",statusCode="404",le="50"} 0
search_api_request_duration_milliseconds_bucket{route="GET /asdasd",statusCode="404",le="100"} 0
search_api_request_duration_milliseconds_bucket{route="GET /asdasd",statusCode="404",le="300"} 0
search_api_request_duration_milliseconds_bucket{route="GET /asdasd",statusCode="404",le="500"} 0
search_api_request_duration_milliseconds_bucket{route="GET /asdasd",statusCode="404",le="700"} 1
search_api_request_duration_milliseconds_bucket{route="GET /asdasd",statusCode="404",le="900"} 1
search_api_request_duration_milliseconds_bucket{route="GET /asdasd",statusCode="404",le="1000"} 1
search_api_request_duration_milliseconds_bucket{route="GET /asdasd",statusCode="404",le="1200"} 1
search_api_request_duration_milliseconds_bucket{route="GET /asdasd",statusCode="404",le="1500"} 1
search_api_request_duration_milliseconds_bucket{route="GET /asdasd",statusCode="404",le="2000"} 1
search_api_request_duration_milliseconds_bucket{route="GET /asdasd",statusCode="404",le="3000"} 1
search_api_request_duration_milliseconds_bucket{route="GET /asdasd",statusCode="404",le="5000"} 1
search_api_request_duration_milliseconds_bucket{route="GET /asdasd",statusCode="404",le="7500"} 1
search_api_request_duration_milliseconds_bucket{route="GET /asdasd",statusCode="404",le="+Inf"} 1
search_api_request_duration_milliseconds_count{route="GET /asdasd",statusCode="404"} 1
search_api_request_duration_milliseconds_sum{route="GET /asdasd",statusCode="404"} 561.01489067078
search_api_request_duration_milliseconds_bucket{route="GET /metrics",statusCode="200",le="50"} 0
search_api_request_duration_milliseconds_bucket{route="GET /metrics",statusCode="200",le="100"} 0
search_api_request_duration_milliseconds_bucket{route="GET /metrics",statusCode="200",le="300"} 40
search_api_request_duration_milliseconds_bucket{route="GET /metrics",statusCode="200",le="500"} 49
search_api_request_duration_milliseconds_bucket{route="GET /metrics",statusCode="200",le="700"} 50
search_api_request_duration_milliseconds_bucket{route="GET /metrics",statusCode="200",le="900"} 50
search_api_request_duration_milliseconds_bucket{route="GET /metrics",statusCode="200",le="1000"} 50
search_api_request_duration_milliseconds_bucket{route="GET /metrics",statusCode="200",le="1200"} 50
search_api_request_duration_milliseconds_bucket{route="GET /metrics",statusCode="200",le="1500"} 50
search_api_request_duration_milliseconds_bucket{route="GET /metrics",statusCode="200",le="2000"} 50
search_api_request_duration_milliseconds_bucket{route="GET /metrics",statusCode="200",le="3000"} 50
search_api_request_duration_milliseconds_bucket{route="GET /metrics",statusCode="200",le="5000"} 50
search_api_request_duration_milliseconds_bucket{route="GET /metrics",statusCode="200",le="7500"} 50
search_api_request_duration_milliseconds_bucket{route="GET /metrics",statusCode="200",le="+Inf"} 50
search_api_request_duration_milliseconds_count{route="GET /metrics",statusCode="200"} 50
search_api_request_duration_milliseconds_sum{route="GET /metrics",statusCode="200"} 13587.605237961
```

`Note` that all HTTP requests are instrumented including the `/metrics` endpoint itself.
