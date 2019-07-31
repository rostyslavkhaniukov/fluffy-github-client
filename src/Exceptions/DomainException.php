<?php
declare(strict_types=1);

namespace Fluffy\GithubClient\Exceptions;

use Throwable;

/**
 * Class DomainException
 * @package Fluffy\GithubClient\Exceptions
 */
class DomainException extends \DomainException
{
    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message ?: $this->retrieveMessage(), $code, $previous);
    }

    /**
     * @return string
     */
    protected function retrieveMessage(): string
    {
        return 'Something went wrong with GitHub API.';
    }
}
