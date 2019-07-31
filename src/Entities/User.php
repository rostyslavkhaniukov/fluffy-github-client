<?php
declare(strict_types=1);

namespace Fluffy\GithubClient\Entities;

/**
 * Class User
 * @package Fluffy\GithubClient\Entities
 */
class User
{
    /** @var mixed */
    public $login;

    /**
     * Release constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->login = $data['login'];
    }

    /**
     * @param array $data
     * @return User
     */
    public static function fromArray(array $data): User
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
