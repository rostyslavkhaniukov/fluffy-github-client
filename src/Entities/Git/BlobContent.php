<?php

declare(strict_types=1);

namespace Fluffy\GithubClient\Entities\Git;

use Fluffy\GithubClient\Entities\AbstractEntity;

class BlobContent extends AbstractEntity
{
    /** @var string */
    private $sha;

    /** @var string */
    private $nodeId;

    /** @var int */
    private $size;

    /** @var string */
    private $content;

    public function __construct(array $data)
    {
        $this->sha = (string) $data['sha'];
        $this->nodeId = (string) $data['node_id'];
        $this->size = (int) $data['size'];
        $this->content = base64_decode((string) $data['content'], true);
    }

    public function getSha(): string
    {
        return $this->sha;
    }

    public function getNodeId(): string
    {
        return $this->nodeId;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
