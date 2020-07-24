<?php

namespace ArtARTs36\ModifierRequestBuilder\Tests\Unit;

use ArtARTs36\ModifierRequestBuilder\Support\KeyReplacer;
use ArtARTs36\ModifierRequestBuilder\Tests\Traits\CallReflectionMethod;
use PHPUnit\Framework\TestCase;

/**
 * Class KeyReplacerTest
 * @package ArtARTs36\ModifierRequestBuilder\Tests\Unit
 * @covers \ArtARTs36\ModifierRequestBuilder\Support\KeyReplacer
 */
class KeyReplacerTest extends TestCase
{
    use CallReflectionMethod;

    /**
     * @covers \ArtARTs36\ModifierRequestBuilder\Support\KeyReplacer
     */
    public function testKey(): void
    {
        $key = 'name';

        $keys = [
            $key => '_name',
            'family' => '_family',
        ];

        $replacer = $this->make();

        $response = $this->callMethodViaReflection($replacer, 'key', $key, $keys);

        self::assertEquals('_name', $response);
    }

    /**
     * @return KeyReplacer
     */
    protected function make(): KeyReplacer
    {
        return new KeyReplacer();
    }
}
