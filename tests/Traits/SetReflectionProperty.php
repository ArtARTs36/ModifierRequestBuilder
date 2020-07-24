<?php

namespace ArtARTs36\ModifierRequestBuilder\Tests\Traits;

trait SetReflectionProperty
{
    /**
     * @param object $object
     * @param string $name
     * @param mixed $value
     * @throws \ReflectionException
     */
    protected function setReflectionProperty(object $object, string $name, $value): void
    {
        $reflection = new \ReflectionObject($object);

        $property = $reflection->getProperty($name);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }
}
