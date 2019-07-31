<?php

namespace Fluffy\GithubClient\Entities;

/**
 * @package Fluffy\GithubClient\Entities
 */
class Ref
{
    /** @var string */
    private $ref;

    /** @var string */
    private $nodeId;

    /** @var string */
    private $url;

    /** @var string */
    public $objectSha;

    public function __construct(array $data)
    {
        $this->ref = $data['ref'];
        $this->nodeId = $data['node_id'];
        $this->url = $data['url'];
        $this->objectSha = $data['object']['sha'];
    }

    public static function fromArray(array $data): Ref
    {
        return new static($data);
    }
}
