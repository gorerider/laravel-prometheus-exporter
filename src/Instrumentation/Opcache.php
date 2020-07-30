<?php

namespace gorerider\PrometheusExporter\Instrumentation;

class Opcache extends AbstractCollector
{
    function collect(): void
    {
        if (!function_exists('opcache_get_status')) {
            return;
        }

        $opcacheStatus = opcache_get_status(false);
        $registry = $this->registry;
        $exportedValues = [
            'opcache_enabled',
            'cache_full',
            'restart_pending',
            'restart_in_progress',
            'memory_usage.used_memory',
            'memory_usage.free_memory',
            'memory_usage.wasted_memory',
            'interned_strings_usage.buffer_size',
            'interned_strings_usage.used_memory',
            'interned_strings_usage.free_memory',
            'interned_strings_usage.number_of_strings',
            'opcache_statistics.num_cached_scripts',
            'opcache_statistics.num_cached_keys',
            'opcache_statistics.max_cached_keys',
            'opcache_statistics.hits',
            'opcache_statistics.start_time',
            'opcache_statistics.last_restart_time',
            'opcache_statistics.oom_restarts',
            'opcache_statistics.manual_restarts',
            'opcache_statistics.misses',
            'opcache_statistics.blacklist_misses',
        ];

        foreach ($exportedValues as $exportedValue){
            if (false !== ($dotPos = strpos($exportedValue, '.'))) {
                $arrKey = substr($exportedValue, 0, $dotPos);
                $subKey = substr($exportedValue, $dotPos + 1);
                $label = str_replace('.', '_', $exportedValue);
                $value = $opcacheStatus[$arrKey][$subKey];
            } else {
                $label = $exportedValue;
                $value = $opcacheStatus[$exportedValue];
            }

            $gauge = $registry->getOrRegisterGauge(
                config('prometheus_exporter.opcache_metrics_namespace'),
                $label,
                ''
            );

            $gauge->set($value);
        }
    }
}
