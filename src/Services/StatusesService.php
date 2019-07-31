<?php
declare(strict_types=1);

namespace Fluffy\GithubClient\Services;

use Fluffy\GithubClient\Entities\Label;
use Fluffy\GithubClient\Entities\Status;
use Fluffy\GithubClient\Http\Client as HttpClient;
use GuzzleHttp\RequestOptions;

/**
 * Class StatusesService
 * @package Fluffy\GithubClient\Services
 */
class StatusesService extends AbstractService
{
    /**
     * @param HttpClient $client
     * @param string $owner
     */
    public function __construct(HttpClient $client, string $owner)
    {
        parent::__construct($client, $owner);
    }

    /**
     * @param string $repository
     * @param string $ref
     * @return array
     */
    public function all(string $repository, string $ref): array
    {
        $response = $this->client->get("/repos/{$this->owner}/{$repository}/commits/{$ref}/statuses");

        $content = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        return Status::fromCollection($content);
    }

    /**
     * @param string $repository
     * @param string $ref
     * @return Label[]
     */
    public function create(string $repository, string $ref): array
    {
        $response = $this->client->post("/repos/{$this->owner}/{$repository}/statuses/{$ref}", [
            RequestOptions::JSON => [
                'state' => 'success',
                'context' => 'ci/circleci: Build Error',
                'node_id' => 'MDEzOlN0YXR1c0NvbnRleHQ2NTMxNzU4MzQy',
            ]
        ]);

        $content = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        //return Label::fromArray($content);
    }
}
