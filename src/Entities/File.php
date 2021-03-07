<?php
declare(strict_types=1);

namespace Fluffy\GithubClient\Entities;

/**
 * @package Fluffy\GithubClient\Entities
 */
class File extends AbstractEntity
{
    /** @var string */
    private $name;

    /** @var string */
    private $sha;

    /** @var string */
    private $content;

    /** @var string */
    private $encoding;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->sha = $data['sha'];
        $this->content = $data['content'];
        $this->encoding = $data['encoding'] ?? '';
    }

    /**
     * @return string
     */
    public function getDecoded(): string
    {
        if ($this->encoding === 'base64') {
            $result = base64_decode($this->content, true);
            if ($result === false) {
                return '';
            }

            return $result;
        }

        return '';
    }
}
