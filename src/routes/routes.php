<?php

use Illuminate\Support\Facades\Route;

Route::get('metrics', 'gorerider\PrometheusExporter\Controllers\MetricsController@metrics')->name('metrics');
