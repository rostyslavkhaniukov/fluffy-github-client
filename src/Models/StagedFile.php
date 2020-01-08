<?php

declare(strict_types=1);

namespace Fluffy\GithubClient\Models;

use Fluffy\GithubClient\Entities\Git\Blob;

class StagedFile
{
    /** @var string */
    private $path;

    /** @var Blob */
    private $blob;

    /**
     * @param string $path
     * @param Blob $blob
     */
    public function __construct(string $path, Blob $blob)
    {
        $this->path = $path;
        $this->blob = $blob;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * @return Blob
     */
    public function getBlob(): Blob
    {
        return $this->blob;
    }

    /**
     * @param Blob $blob
     */
    public function setBlob(Blob $blob): void
    {
        $this->blob = $blob;
    }
}
