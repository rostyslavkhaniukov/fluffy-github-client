<?php

declare(strict_types=1);

namespace Fluffy\GithubClient\Entities;

/**
 * @package Fluffy\GithubClient\Entities
 */
class SecretsKey
{
    private string $keyId = '';
    private string $key = '';

    public function __construct(array $data)
    {
        $this->keyId = $data['key_id'];
        $this->key = $data['key'];
    }

    public static function fromArray(array $data): SecretsKey
    {
        return new self($data);
    }

    public function getKeyId(): string
    {
        return $this->keyId;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
