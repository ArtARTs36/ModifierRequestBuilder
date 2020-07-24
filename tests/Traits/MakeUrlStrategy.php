<?php

namespace ArtARTs36\ModifierRequestBuilder\Tests\Traits;

use ArtARTs36\ModifierRequestBuilder\Contracts\UrlStrategy;

trait MakeUrlStrategy
{
    public function makeUrlStrategy(): UrlStrategy
    {
        return new class implements UrlStrategy {
            /**
             * @inheritDoc
             */
            public function url(string $action, string $id = null): string
            {
                return '';
            }

            /**
             * @inheritDoc
             */
            public function method(string $action, string $id = null): string
            {
                return 'GET';
            }
        };
    }
}
