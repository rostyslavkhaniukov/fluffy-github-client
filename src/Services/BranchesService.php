<?php
declare(strict_types=1);

namespace Fluffy\GithubClient\Services;

use Fluffy\GithubClient\Entities\Branch;
use Fluffy\GithubClient\Entities\File;
use Fluffy\GithubClient\Entities\Label;
use Fluffy\GithubClient\Http\Client as HttpClient;

/**
 * @package Fluffy\GithubClient\Services
 */
class BranchesService extends AbstractService
{
    /**
     * @param HttpClient $client
     */
    public function __construct(HttpClient $client)
    {
        parent::__construct($client, '');
    }

    /**
     * @param string $owner
     * @param string $repository
     * @param string $name
     * @return Branch
     */
    public function get(string $owner, string $repository, string $name): Branch
    {
        $response = $this->client->get("/repos/{$owner}/{$repository}/branches/{$name}");

        $content = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        return Branch::fromArray($content);
    }
}
