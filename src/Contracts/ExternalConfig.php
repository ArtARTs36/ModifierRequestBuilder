<?php

namespace ArtARTs36\ModifierRequestBuilder\Contracts;

/**
 * Interface ExternalConfig
 * @package ArtARTs36\ModifierRequestBuilder\Contracts
 */
interface ExternalConfig
{
    /**
     * @return string
     */
    public function delimiterFields(): string;

    /**
     * @return string
     */
    public function delimiterAssociations(): string;
}
