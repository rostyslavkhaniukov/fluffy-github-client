<?php

declare(strict_types=1);

namespace Fluffy\GithubClient\Services;

use Fluffy\GithubClient\Entities\Comparation;
use Fluffy\GithubClient\Http\Client as HttpClient;

/**
 * @package Fluffy\GithubClient\Services
 */
class CompareService extends AbstractService
{
    /**
     * @param HttpClient $client
     */
    public function __construct(HttpClient $client)
    {
        parent::__construct($client, '' );
    }

    /**
     * @param string $owner
     * @param string $repository
     * @param string $content
     * @param string $encoding
     * @return Comparation
     */
    public function diff(string $owner, string $repository, string $baseBranch, string $branch): Comparation
    {
        $response = $this->client->get("/repos/{$owner}/{$repository}/compare/{$baseBranch}...{$branch}");

        $content = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        return Comparation::fromArray($content);
    }
}
