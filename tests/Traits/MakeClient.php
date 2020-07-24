<?php

namespace ArtARTs36\ModifierRequestBuilder\Tests\Traits;

use ArtARTs36\ModifierRequestBuilder\Contracts\Client;

/**
 * Trait MakeClient
 * @package ArtARTs36\ModifierRequestBuilder\Tests\Traits
 */
trait MakeClient
{
    /**
     * @param array $returnData
     * @return Client
     */
    protected function makeClient(array $returnData = []): Client
    {
        return new class($returnData) implements Client {
            private $returnData;

            public function __construct(array $returnData = [])
            {
                $this->returnData = $returnData;
            }

            /**
             * @inheritDoc
             */
            public function json(string $method, string $uri, array $options = []): array
            {
                return $this->returnData;
            }
        };
    }
}
