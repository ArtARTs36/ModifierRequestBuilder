<?php

namespace ArtARTs36\ModifierRequestBuilder\Support;

use ArtARTs36\ModifierRequestBuilder\Builder;

/**
 * Trait Compilers
 * @package ArtARTs36\ModifierRequestBuilder\Support
 */
trait Compilers
{
    /**
     * @return $this
     */
    protected function compileSort(): ?string
    {
        return implode(
            $this->externalConfig->delimiterFields(),
            array_map(function ($column, $direction) {
                return ($direction === Builder::SORT_DIRECTION_DESC ? '-' : '') . $column;
            }, array_keys($this->request['_sort']), $this->request['_sort'])
        );
    }

    /**
     * @return string|null
     */
    protected function compileSelect(): ?string
    {
        return implode($this->externalConfig->delimiterFields(), $this->request['_select']);
    }

    /**
     * @return string|null
     */
    protected function compileWith(): ?string
    {
        return implode($this->externalConfig->delimiterFields(), $this->request['_with']);
    }
}
