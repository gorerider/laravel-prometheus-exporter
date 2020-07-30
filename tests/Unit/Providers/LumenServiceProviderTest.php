<?php
declare(strict_types=1);

namespace gorerider\Tests\Unit\Providers;

use Prometheus\Storage\InMemory;
use gorerider\Tests\BaseTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use gorerider\PrometheusExporter\Providers\LumenServiceProvider;

class LumenServiceProviderTest extends BaseTestCase
{
    public function testRegister()
    {
        /** @var LumenServiceProvider|MockObject $provider */
        $provider = $this->getMockBuilder(LumenServiceProvider::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getConfig', 'configureInstrumentation', 'getAdapter', 'registerCollectorRegistry'])
            ->getMock();
        
        $provider
            ->expects($this->once())
            ->method('getConfig')
            ->with('adapter')
            ->willReturn('memory');

        $provider
            ->expects($this->once())
            ->method('configureInstrumentation');

        $provider
            ->expects($this->once())
            ->method('getAdapter')
            ->willReturn(new InMemory());

        $provider
            ->expects($this->once())
            ->method('registerCollectorRegistry');

        $provider->register();
    }
}
