<?php
declare(strict_types=1);

namespace Fluffy\GithubClient\Services;

use Fluffy\GithubClient\Http;

/**
 * Class AbstractService
 * @package Fluffy\GithubClient\Services
 */
class AbstractService
{
    /** @var Http\Client */
    protected $client;

    /** @var string */
    protected $owner;

    /**
     * @param Http\Client $client
     * @param string $owner
     */
    public function __construct(Http\Client $client, string $owner)
    {
        $this->client = $client;
        $this->owner = $owner;
    }
}
