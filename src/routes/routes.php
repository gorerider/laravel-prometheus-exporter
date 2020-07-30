<?php

use Illuminate\Support\Facades\Route;

Route::get('metrics', 'gorerider\PrometheusExporter\Controllers\LaravelMetricsController@metrics')
    ->name('metrics');
