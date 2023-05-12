<?php
declare(strict_types=1);

namespace Tests\Enjoys\Forms;

/**
 * Trait Reflection
 * @package Enjoys\Traits
 */
trait Reflection
{

    /**
     * getPrivateMethod
     *
     * @param class-string<object>|object $className
     * @param string $methodName
     * @return    \ReflectionMethod
     * @throws \ReflectionException
     * @author    Joe Sexton <joe@webtipblog.com>
     */
    public function getPrivateMethod($className, string $methodName): \ReflectionMethod
    {
        $reflector = new \ReflectionClass($className);
        $method = $reflector->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * getPrivateProperty
     *
     * @param class-string<object>|object $className
     * @param string $propertyName
     * @return \ReflectionProperty
     * @throws \ReflectionException
     * @author    Joe Sexton <joe@webtipblog.com>
     */
    public function getPrivateProperty($className, string $propertyName): \ReflectionProperty
    {
        $reflector = new \ReflectionClass($className);
        $property = $reflector->getProperty($propertyName);
        $property->setAccessible(true);

        return $property;
    }

}
