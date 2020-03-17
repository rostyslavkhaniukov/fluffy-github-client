<?php

declare(strict_types=1);

namespace Fluffy\GithubClient\Entities;

/**
 * @package Fluffy\GithubClient\Entities
 */
class SecretsKey
{
    /** @var string */
    private $keyId = '';

    /** @var string */
    private $key = '';

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->keyId = $data['key_id'];
        $this->key = $data['key'];
    }

    /**
     * @param array $data
     * @return Tag
     */
    public static function fromArray(array $data): SecretsKey
    {
        return new static($data);
    }

    /**
     * @return string
     */
    public function getKeyId(): string
    {
        return $this->keyId;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }
}
