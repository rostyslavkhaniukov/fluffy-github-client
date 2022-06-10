<?php

declare(strict_types=1);

namespace Fluffy\GithubClient\Entities;

class Ref
{
    private string $ref;
    private string $nodeId;
    private string $url;
    public string $objectSha;

    public function __construct(array $data)
    {
        $this->ref = (string)$data['ref'];
        $this->nodeId = (string)$data['node_id'];
        $this->url = (string)$data['url'];
        $this->objectSha = (string)$data['object']['sha'];
    }

    public static function fromArray(array $data): Ref
    {
        return new self($data);
    }

    public function getRef(): string
    {
        return $this->ref;
    }

    /**
     * @return string
     */
    public function getNodeId(): string
    {
        return $this->nodeId;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}
