<?php
declare(strict_types=1);

namespace gorerider\Tests\Unit\Middleware;

use Prometheus\Histogram;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Prometheus\CollectorRegistry;
use gorerider\Tests\BaseTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use gorerider\PrometheusExporter\Middleware\PrometheusExporterMiddleware;

class PrometheusExporterMiddlewareTest extends BaseTestCase
{
    public function testTerminate()
    {
        $histogram = $this->getMockBuilder(Histogram::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['observe'])
            ->getMock();

        $histogram
            ->expects($this->once())
            ->method('observe');

        /** @var CollectorRegistry|MockObject $registry */
        $registry = $this->getMockBuilder(CollectorRegistry::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getOrRegisterHistogram'])
            ->getMock();

        $registry
            ->expects($this->once())
            ->method('getOrRegisterHistogram')
            ->willReturn($histogram);

        (new PrometheusExporterMiddleware($registry))
            ->terminate(new Request(), new Response());
    }

    public function testRouteDetails()
    {
        /** @var PrometheusExporterMiddleware|MockObject $middleware */
        $middleware = $this->getMockBuilder(PrometheusExporterMiddleware::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var Request|MockObject $request */
        $request = $this->getMockBuilder(Request::class)
            ->onlyMethods(['server', 'getMethod', 'getPathInfo'])
            ->getMock();

        $request
            ->expects($this->once())
            ->method('server')
            ->with('REQUEST_TIME_FLOAT')
            ->willReturn(100.0);

        $request
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn('GET');

        $request
            ->expects($this->once())
            ->method('getPathInfo')
            ->willReturn('/some-path');

        /** @var Response|MockObject $request */
        $response = $this->getMockBuilder(Response::class)
            ->onlyMethods(['getStatusCode'])
            ->getMock();

        $response
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        $getRouteDetailsMethod = $this->getMethod(PrometheusExporterMiddleware::class, 'getRouteDetails');

        [
            'route' => $actualRoute,
            'statusCode' => $actualStatusCode,
            'durationMs' => $actualDurationMs,
        ] = $getRouteDetailsMethod->invokeArgs($middleware, [$request, $response]);

        $this->assertEquals('GET /some-path', $actualRoute);
        $this->assertEquals(200, $actualStatusCode);
        $this->assertIsFloat($actualDurationMs);
    }
}
