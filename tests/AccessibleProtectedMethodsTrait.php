<?php
declare(strict_types=1);

namespace gorerider\Tests;

use ReflectionMethod;
use ReflectionException;

trait AccessibleProtectedMethodsTrait
{
    /**
     * Makes protected/private method public
     *
     * @param string $class Class with method name
     * @param string $methodName The method name to make public
     *
     * @return ReflectionMethod
     *
     * @throws ReflectionException
     */
    public function getMethod(string $class, string $methodName): ReflectionMethod
    {
        $class = new \ReflectionClass($class);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);
        return $method;
    }
}
