<?php

declare(strict_types=1);

namespace Fluffy\GithubClient\Entities;

/**
 * @package Fluffy\GithubClient\Entities
 */
class FilesystemEntity extends AbstractEntity
{
    public const TYPE_FILE = 'file';
    public const TYPE_DIR = 'dir';

    /** @var string */
    private $name;

    /** @var string */
    private $path;

    /** @var string */
    private $sha;

    /** @var int */
    private $size;

    /** @var string */
    private $type;

    public function __construct(array $data)
    {
        $this->name = (string) $data['name'];
        $this->path = (string) $data['path'];
        $this->sha = (string) $data['sha'];
        $this->size = (int) $data['size'];
        $this->type = (string) $data['type'];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getSha(): string
    {
        return $this->sha;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isDirectory(): bool
    {
        return $this->getType() === self::TYPE_DIR;
    }

    public function isFile(): bool
    {
        return $this->getType() === self::TYPE_FILE;
    }
}
