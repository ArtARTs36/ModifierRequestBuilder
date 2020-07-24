<?php

namespace ArtARTs36\ModifierRequestBuilder\Contracts;

/**
 * Interface UrlStrategy
 * @package ArtARTs36\ModifierRequestBuilder\Contracts
 */
interface UrlStrategy
{
    /**
     * @param string $action
     * @param string|null $id
     * @return string
     */
    public function url(string $action, string $id = null): string;

    /**
     * @param string $action
     * @param string|null $id
     * @return string
     */
    public function method(string $action, string $id = null): string;
}
