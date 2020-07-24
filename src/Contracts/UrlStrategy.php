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
     * @return string
     */
    public function bring(string $action): string;
}
