<?php

namespace gorerider\PrometheusExporter\Instrumentation;

class FPM extends AbstractCollector
{
    function collect(): void
    {
        if (!function_exists('fpm_get_status')){
            return;
        }

        $fpmStatus = fpm_get_status();
        $registry = $this->registry;
        $metricsNamespace = config('prometheus_exporter.fpm_metrics_namespace');

        $startTime = $registry->getOrRegisterGauge($metricsNamespace, 'start_time', 'start_time');
        $startTime->set($fpmStatus['start-time']);

        $acceptedConn = $registry->getOrRegisterGauge($metricsNamespace, 'accepted_conn', 'accepted_conn');
        $acceptedConn->set($fpmStatus['accepted-conn']);

        $listenQueue = $registry->getOrRegisterGauge($metricsNamespace, 'listen_queue', 'listen_queue');
        $listenQueue->set($fpmStatus['listen-queue']);

        $maxListenQueue = $registry->getOrRegisterGauge($metricsNamespace, 'max_listen_queue', 'max_listen_queue');
        $maxListenQueue->set($fpmStatus['max-listen-queue']);

        $listenQueueLen = $registry->getOrRegisterGauge($metricsNamespace, 'listen_queue_len', 'listen_queue_len');
        $listenQueueLen->set($fpmStatus['listen-queue-len']);

        $idleProcesses = $registry->getOrRegisterGauge($metricsNamespace, 'idle_processes', 'idle_processes');
        $idleProcesses->set($fpmStatus['idle-processes']);

        $activeProcesses = $registry->getOrRegisterGauge($metricsNamespace, 'active_processes', 'active_processes');
        $activeProcesses->set($fpmStatus['active-processes']);

        $maxActiveProcesses = $registry->getOrRegisterGauge($metricsNamespace, 'max_active_processes', 'max_active_processes');
        $maxActiveProcesses->set($fpmStatus['max-active-processes']);

        $maxChildrenReached = $registry->getOrRegisterGauge($metricsNamespace, 'max_children_reached', 'max_children_reached');
        $maxChildrenReached->set($fpmStatus['max-children-reached']);

        $slowRequests = $registry->getOrRegisterGauge($metricsNamespace, 'slow_requests', 'slow_requests');
        $slowRequests->set($fpmStatus['slow-requests']);

        $procStartTime = $registry->getOrRegisterGauge($metricsNamespace, 'proc_start_time', 'proc start time', ['pid']);
        $procRequests = $registry->getOrRegisterGauge($metricsNamespace, 'proc_requests', 'proc requests', ['pid']);

        foreach ($fpmStatus['procs'] as $proc){
            $procStartTime->set($proc['start-time'], [$proc['pid']]);
            $procRequests->set($proc['requests'], [$proc['pid']]);
        }
    }
}
