<?php

declare(strict_types=1);

namespace Fluffy\GithubClient\Entities\Git;

/**
 * @package Fluffy\GithubClient\Entities\Git
 */
class Commit
{
    private array $author;
    private array $commiter;
    private string $message;
    public Tree $tree;
    private string $url;
    private int $commentCount;
    private ?string $sha;

    public function __construct(array $data)
    {
        $this->sha = (string)($data['sha'] ?? null);
        $this->author = (array)($data['author'] ?? []);
        $this->commiter = (array)($data['commiter'] ?? []);
        $this->message = (string)($data['message'] ?? '');
        $this->tree = Tree::fromArray($data['tree'] ?? []);
        $this->url = (string)($data['url'] ?? '');
        $this->commentCount = (int)($data['comment_count'] ?? 0);
    }

    /**
     * @param array $data
     * @return Commit
     */
    public static function fromArray(array $data): Commit
    {
        return new self($data);
    }

    /**
     * @return string|null
     */
    public function getSha(): ?string
    {
        return $this->sha;
    }

    /**
     * @param string|null $sha
     */
    public function setSha(?string $sha): void
    {
        $this->sha = $sha;
    }

    public function getAuthor(): array
    {
        return $this->author;
    }

    public function getCommiter(): array
    {
        return $this->commiter;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getCommentCount(): int
    {
        return $this->commentCount;
    }
}
