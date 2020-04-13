<?php

declare(strict_types=1);

namespace Fluffy\GithubClient\Services;

use Fluffy\GithubClient\Entities\Release;
use Fluffy\GithubClient\Http\Client as HttpClient;

/**
 * @package Fluffy\GithubClient\Services
 */
class ReleasesService extends AbstractService
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
     * @param string $tagName
     * @param string $body
     * @return Release
     */
    public function create(string $owner, string $repository, string $tagName, string $body = ''): Release
    {
        $response = $this->client->post("/repos/{$owner}/{$repository}/releases", [
            'tag_name' => $tagName,
            'name' => $tagName,
            'body' => $body
        ]);

        $content = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        return Release::fromArray($content);
    }
}
