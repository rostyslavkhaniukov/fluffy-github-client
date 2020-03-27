<?php
declare(strict_types=1);

namespace Fluffy\GithubClient\Entities;

/**
 * @package Fluffy\GithubClient\Entities
 */
class Commit
{
    /** @var string */
    public $sha;

    /** @var string */
    public $message;

    /** @var string */
    public $title;

    /** @var Git\Commit */
    public $commit;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->sha = (string)$data['sha'];
        $this->title = (string)($data['title'] ?? '');
        $this->message = (string)($data['commit']['message'] ?? '');
        $this->commit = Git\Commit::fromArray($data['commit'] ?? []);
    }

    /**
     * @param array $data
     * @return Commit
     */
    public static function fromArray(array $data): Commit
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
