<?php

declare(strict_types=1);

namespace Fluffy\GithubClient\Entities;

/**
 * @package Fluffy\GithubClient\Entities
 */
class Comparation
{
    /** @var int */
    private $totalCommits;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->totalCommits = $data['total_commits'] ?? 0;
    }

    /**
     * @param array $data
     * @return Comparation
     */
    public static function fromArray(array $data): Comparation
    {
        return new static($data);
    }

    /**
     * @return bool
     */
    public function haveDiff(): bool
    {
        return $this->totalCommits > 0;
    }
}
