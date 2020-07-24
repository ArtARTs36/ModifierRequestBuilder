<?php

namespace ArtARTs36\ModifierRequestBuilder\Contracts;

/**
 * Interface Client
 * @package ArtARTs36\ModifierRequestBuilder\Contracts
 */
interface Client
{
    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return array
     */
    public function json(string $method, string $uri, array $options = []): array;
}
