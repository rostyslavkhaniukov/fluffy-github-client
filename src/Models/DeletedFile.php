<?php

declare(strict_types=1);

namespace Fluffy\GithubClient\Models;

class DeletedFile
{
    /** @var string */
    private $path;

    /**
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}
