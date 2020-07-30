<?php
declare(strict_types=1);

namespace gorerider\Tests\Unit\Providers;

use gorerider\Tests\BaseTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use gorerider\PrometheusExporter\Providers\AbstractServiceProvider;

class AbstractServiceProviderTest extends BaseTestCase
{
    public function testKnowsMemoryAdapter()
    {
        $class = AbstractServiceProvider::class;

        /** @var AbstractServiceProvider|MockObject $provider */
        $provider = $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getMemoryAdapter'])
            ->getMock();
        
        $provider
            ->expects($this->once())
            ->method('getMemoryAdapter');

        $getAdapterMethod = $this->getMethod($class, 'getAdapter');
        $getAdapterMethod->invokeArgs($provider, ['memory']);
    }

    public function testKnowsRedisAdapter()
    {
        $class = AbstractServiceProvider::class;

        /** @var AbstractServiceProvider|MockObject $provider */
        $provider = $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getRedisAdapter'])
            ->getMock();

        $provider
            ->expects($this->once())
            ->method('getRedisAdapter');

        $getAdapterMethod = $this->getMethod($class, 'getAdapter');
        $getAdapterMethod->invokeArgs($provider, ['redis']);
    }

    public function testKnowsApcAdapter()
    {
        $class = AbstractServiceProvider::class;

        /** @var AbstractServiceProvider $provider */
        $provider = $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getApcAdapter'])
            ->getMock();

        $provider
            ->expects($this->once())
            ->method('getApcAdapter');

        $getAdapterMethod = $this->getMethod($class, 'getAdapter');
        $getAdapterMethod->invokeArgs($provider, ['apc']);
    }

    public function testUnknownAdapterThrowsException()
    {
        $class = AbstractServiceProvider::class;

        /** @var AbstractServiceProvider $provider */
        $provider = $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->expectException(\InvalidArgumentException::class);
        
        $getAdapterMethod = $this->getMethod($class, 'getAdapter');
        $getAdapterMethod->invokeArgs($provider, ['unknown']);
    }
}
