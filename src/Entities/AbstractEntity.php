<?php

declare(strict_types=1);

namespace Fluffy\GithubClient\Entities;

abstract class AbstractEntity
{
    /**
     * @param array $data
     */
    abstract public function __construct(array $data);

    /**
     * @param array $data
     * @return static
     */
    public static function fromArray(array $data)
    {
        return new static($data);
    }

    /**
     * @param array $data
     * @return static[]
     */
    public static function fromCollection(array $data): array
    {
        return array_map(function (array $item) {
            return static::fromArray($item);
        }, $data);
    }
}
