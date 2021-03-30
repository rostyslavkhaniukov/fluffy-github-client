<?php
declare(strict_types=1);

namespace Fluffy\GithubClient\Entities;

/**
 * @package Fluffy\GithubClient\Entities
 */
class Directory extends AbstractEntity
{
    /** @var FilesystemEntity[] */
    private $filesystemEntities;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->filesystemEntities = FilesystemEntity::fromCollection($data);
    }

    /**
     * @return FilesystemEntity[]
     */
    public function getFilesystemEntities(): array
    {
        return $this->filesystemEntities;
    }
}
