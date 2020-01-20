<?php

namespace Fluffy\GithubClient\Entities;

/**
 * Class PullRequest
 * @package Fluffy\GithubClient\Entities
 */
class PullRequest
{
    public $mergedAt;
    public $head;
    public $mergeCommitSha;

    /** @var string */
    public $htmlUrl;
    public $number;

    /** @var array */
    public $labels;

    /** @var string */
    public $title;

    /** @var User */
    public $author;

    /** @var array */
    public $base;

    public function __construct(array $data)
    {
        $this->number = $data['number'];
        $this->mergedAt = $data['merged_at'];
        $this->head = Commit::fromArray($data['head']);
        $this->mergeCommitSha = $data['merge_commit_sha'];
        $this->title = $data['title'];
        $this->htmlUrl = $data['html_url'];
        $this->labels = Label::fromCollection((array)$data['labels']);
        $this->author = User::fromArray($data['user']);
        $this->base = $data['base'] ?? [];
    }

    public static function fromArray(array $data): PullRequest
    {
        return new static($data);
    }

    /**
     * @param array $data
     * @return PullRequest[]
     */
    public static function fromCollection(array $data): array
    {
        return array_map(function (array $item) {
            return static::fromArray($item);
        }, $data);
    }

    /**
     * @return array
     */
    public function getLabels(): array
    {
        return $this->labels;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }
}
