<?php

namespace ArtARTs36\ModifierRequestBuilder\Support;

/**
 * Class KeyReplacer
 * @package ArtARTs36\ModifierRequestBuilder\Support
 */
class KeyReplacer
{
    /**
     * @param array $data
     * @param array $keys
     * @return array
     */
    public function replace(array $data, array $keys): array
    {
        $newData = [];

        foreach ($data as $key => $value) {
            $newData[$this->key($key, $keys)] = $value;
        }

        return $newData;
    }

    /**
     * @param array $data
     * @param array $keys
     * @return array
     */
    public function replaceMany(array $data, array $keys): array
    {
        return array_map(function (array $item) use ($keys) {
            return $this->replace($item, $keys);
        }, $data);
    }

    /**
     * @param mixed $key
     * @param array $keys
     * @return mixed
     */
    protected function key($key, array $keys)
    {
        return $keys[$key] ?? $key;
    }
}
