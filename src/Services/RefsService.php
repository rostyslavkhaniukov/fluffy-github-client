<?php
declare(strict_types=1);

namespace Fluffy\GithubClient\Services;

use Fluffy\GithubClient\Entities\Ref;
use Fluffy\GithubClient\Http\Client as HttpClient;
use GuzzleHttp\RequestOptions;

/**
 * @package Fluffy\GithubClient\Services
 */
class RefsService extends AbstractService
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
     * @param string $ref
     * @param string $sha
     * @return Ref
     */
    public function createRef(string $owner, string $repository, string $ref, string $sha): Ref
    {
        $response = $this->client->post("/repos/{$owner}/{$repository}/git/refs", [
            RequestOptions::JSON => [
                'ref' => $ref,
                'sha' => $sha,
            ]
        ]);

        $content = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        return Ref::fromArray($content);
    }

    /**
     * @param string $owner
     * @param string $repository
     * @param string $ref
     * @param string $commitSha
     * @return Ref
     */
    public function updateRef(string $owner, string $repository, string $ref, string $commitSha): Ref
    {
        $response = $this->client->patch("/repos/{$owner}/{$repository}/git/refs/{$ref}", [
            RequestOptions::JSON => [
                'sha' => $commitSha,
                'force' => false,
            ]
        ]);

        $content = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        return Ref::fromArray($content);
    }

    /**
     * @param string $owner
     * @param string $repository
     * @return Ref
     */
    public function all(string $owner, string $repository): Ref
    {
        $response = $this->client->get("/repos/{$owner}/{$repository}/git/refs");

        $content = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        return Ref::fromArray($content);
    }
}
