<?php

namespace ArtARTs36\ModifierRequestBuilder\Contracts;

interface Client
{
    public function json(string $method, string $uri, array $options = []): array;
}
