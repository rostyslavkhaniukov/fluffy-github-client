<?php

declare(strict_types=1);

namespace Fluffy\GithubClient\Entities;

/**
 * @package Fluffy\GithubClient\Entities
 */
class FilesystemEntity
{
    /**
     * @param array $data
     * @return File
     */
    public static function fromArray(array $data): File
    {
        var_dump($data);
    }
}