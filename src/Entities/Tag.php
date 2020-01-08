<?php

namespace Fluffy\GithubClient\Entities;

/**
 * @package Fluffy\GithubClient\Entities
 */
class Tag
{
    /** @var string */
    private $name;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
    }

    public static function fromArray(array $data): Tag
    {
        return new static($data);
    }

    /**
     * @param array $data
     * @return array
     */
    public static function fromCollection(array $data): array
    {
        return array_map(function (array $item) {
            return static::fromArray($item);
        }, $data);
    }
}
