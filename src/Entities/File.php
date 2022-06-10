<?php

declare(strict_types=1);

namespace Fluffy\GithubClient\Entities;

class File extends AbstractEntity
{
    private string $name;
    private string $sha;
    private string $content;
    private string $encoding;

    public function __construct(array $data)
    {
        $this->name = (string)$data['name'];
        $this->sha = (string)$data['sha'];
        $this->content = (string)$data['content'];
        $this->encoding = (string)($data['encoding'] ?? '');
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

    public function getName(): string
    {
        return $this->name;
    }

    public function getSha(): string
    {
        return $this->sha;
    }
}
