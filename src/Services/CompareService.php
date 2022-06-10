<?php

declare(strict_types=1);

namespace Fluffy\GithubClient\Services;

use Fluffy\GithubClient\Entities\Comparation;
use Fluffy\GithubClient\Http\Client as HttpClient;
use GuzzleHttp\Utils;

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
        parent::__construct($client, '');
    }

    public function diff(string $owner, string $repository, string $baseBranch, string $branch): Comparation
    {
        $response = $this->client->get("/repos/{$owner}/{$repository}/compare/{$baseBranch}...{$branch}");

        $content = Utils::jsonDecode($response->getBody()->getContents(), true);

        return Comparation::fromArray($content);
    }
}
