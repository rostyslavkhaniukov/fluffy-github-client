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
        $this->name = $data['tag'] ?? $data['name'];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param array $data
     * @return Tag
     */
    public static function fromArray(array $data): Tag
    {
        return new self($data);
    }

    /**
     * @param array $data
     * @return Tag[]
     */
    public static function fromCollection(array $data): array
    {
        return array_map(function (array $item) {
            return static::fromArray($item);
        }, $data);
    }
}
