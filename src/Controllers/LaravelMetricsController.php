<?php
declare(strict_types=1);

namespace gorerider\PrometheusExporter\Controllers;

use Illuminate\Routing\Controller;

class LaravelMetricsController extends Controller
{
    use MetricsTrait;
}
