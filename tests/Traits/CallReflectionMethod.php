<?php

namespace ArtARTs36\ModifierRequestBuilder\Tests\Traits;

/**
 * Trait CallReflectionMethod
 * @package ArtARTs36\ModifierRequestBuilder\Tests\Traits
 */
trait CallReflectionMethod
{
    /**
     * @param object $object
     * @param string $method
     * @param mixed ...$params
     * @return mixed
     * @throws \ReflectionException
     */
    protected function callMethodViaReflection(object $object, string $method, ...$params)
    {
        $reflector = new \ReflectionObject($object);

        $method = $reflector->getMethod($method);
        $method->setAccessible(true);

        return $method->invoke($object, ...$params);
    }
}
