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
     * @covers \ArtARTs36\ModifierRequestBuilder\Support\KeyReplacer::replace
     */
    public function testReplace(): void
    {
        $keys = [
            'name' => '_name',
            'patronymic' => '_patronymic',
        ];

        $replacer = $this->make();

        $data = [
            'name' => 'Artem',
            'patronymic' => 'Viktorovich',
        ];

        $response = $replacer->replace($data, $keys);

        self::assertEquals([
            '_name' => 'Artem',
            '_patronymic' => 'Viktorovich',
        ], $response);
    }

    /**
     * @covers \ArtARTs36\ModifierRequestBuilder\Support\KeyReplacer::replaceMany
     */
    public function testReplaceMany(): void
    {
        $replacer = $this->make();

        $data = [
            [
                'name' => 'Artem',
            ],
            [
                'name' => 'Viktor',
            ],
        ];

        $keys = ['name' => '_name'];

        //

        $response = $replacer->replaceMany($data, $keys);

        self::assertEquals([
            [
                '_name' => 'Artem',
            ],
            [
                '_name' => 'Viktor',
            ]
        ], $response);
    }

    /**
     * @return KeyReplacer
     */
    protected function make(): KeyReplacer
    {
        return new KeyReplacer();
    }
}
