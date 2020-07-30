<?php

namespace gorerider\Tests\Component\Controllers;

use gorerider\Tests\BaseTestCase;
use Illuminate\Foundation\Http\Kernel;
use Illuminate\Contracts\Http\Kernel as ContractKernel;
use gorerider\PrometheusExporter\Providers\LaravelServiceProvider;

class MetricsControllerTest extends BaseTestCase
{
    public function getPackageProviders($app)
    {
        return [
            LaravelServiceProvider::class
        ];
    }

    protected function resolveApplicationHttpKernel($app)
    {
         $app->singleton(ContractKernel::class, Kernel::class);
    }

    public function testMetricsEndpoint()
    {
        // we need to make first request, because the middleware collects metrics on terminate
        $this->get('/');
        $response = $this->get('/metrics');

        $actualStatusCode = $response->getStatusCode();
        $actualResponse = $response->getContent();

        $this->assertEquals(200, $actualStatusCode);
        $this->assertTrue(false !== strpos($actualResponse, 'request_duration_milliseconds_sum'));
    }
}
