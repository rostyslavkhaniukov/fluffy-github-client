<?php

declare(strict_types=1);

namespace Fluffy\GithubClient\Entities;

/**
 * @package Fluffy\GithubClient\Entities
 */
class Branch
{
    public Commit $commit;
    private string $name;

    public function __construct(array $data)
    {
        $this->name = (string)($data['name'] ?? '');
        $this->commit = Commit::fromArray($data['commit']);
    }

    public static function fromArray(array $data): Branch
    {
        return new self($data);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
