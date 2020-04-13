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

    /** @var Commit[] */
    private $commits;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->totalCommits = $data['total_commits'] ?? 0;
        $this->commits = Commit::fromCollection($data['commits'] ?? []);
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
    public function hasDiff(): bool
    {
        return $this->totalCommits > 0;
    }

    /**
     * @return Commit[]
     */
    public function getCommits(): array
    {
        return $this->commits;
    }

    /**
     * @return Commit|null
     */
    public function getLastCommit(): ?Commit
    {
        return count($this->commits) > 0 ? reset($this->commits) : null;
    }
}
