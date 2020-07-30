<?php
declare(strict_types=1);

namespace gorerider\PrometheusExporter\Controllers;

use Laravel\Lumen\Routing\Controller;

class LumenMetricsController extends Controller
{
    use MetricsTrait;
}
