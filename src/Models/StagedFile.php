<?php
declare(strict_types=1);

namespace Fluffy\GithubClient\Models;

use Fluffy\GithubClient\Entities\Git\Blob;

/**
 * @package Fluffy\GithubClient\Models
 */
class StagedFile
{
    /** @var string */
    private $filePath;

    /** @var Blob */
    private $blob;

    public function __construct(string $filePath, Blob $blob)
    {
        $this->filePath = $filePath;
        $this->blob = $blob;
    }

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @param string $filePath
     */
    public function setFilePath(string $filePath): void
    {
        $this->filePath = $filePath;
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
