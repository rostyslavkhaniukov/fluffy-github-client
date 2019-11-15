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
     * @return File|Directory
     */
    public static function fromArray(array $data)
    {
        if (array_key_exists('name', $data)) {
            return new File($data);
        }

        return new Directory($data);
    }
}