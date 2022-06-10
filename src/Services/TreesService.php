<?php

declare(strict_types=1);

namespace Fluffy\GithubClient\Services;

use Fluffy\GithubClient\Entities\Git\Tree;
use Fluffy\GithubClient\Http\Client as HttpClient;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Utils;

/**
 * @package Fluffy\GithubClient\Services
 */
class TreesService extends AbstractService
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
     * @param array $tree
     * @return Tree
     */
    public function createTree(string $owner, string $repository, array $tree): Tree
    {
        $response = $this->client->post("/repos/{$owner}/{$repository}/git/trees", [
            RequestOptions::JSON => $tree
        ]);

        $content = Utils::jsonDecode($response->getBody()->getContents(), true);

        return Tree::fromArray($content);
    }

    /**
     * @param string $owner
     * @param string $repository
     * @param string $treeSha
     * @return Tree
     */
    public function get(string $owner, string $repository, string $treeSha, bool $isRecursive = false): Tree
    {
        $response = $this->client->get("/repos/{$owner}/{$repository}/git/trees/{$treeSha}", [
            RequestOptions::QUERY => [
                'recursive' => $isRecursive,
            ],
        ]);

        $content = Utils::jsonDecode($response->getBody()->getContents(), true);

        return Tree::fromArray($content);
    }
}
