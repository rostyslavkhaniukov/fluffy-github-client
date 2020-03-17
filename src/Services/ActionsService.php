<?php

declare(strict_types=1);

namespace Fluffy\GithubClient\Services;

use Fluffy\GithubClient\Entities\SecretsKey;
use Fluffy\GithubClient\Http\Client as HttpClient;

/**
 * @package Fluffy\GithubClient\Services
 */
class ActionsService extends AbstractService
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
     * @param string $owner
     * @param string $repository
     * @return SecretsKey
     */
    public function secrets(string $owner, string $repository): SecretsKey
    {
        $response = $this->client->get("/repos/{$owner}/{$repository}/actions/secrets/public-key");

        $content = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        return SecretsKey::fromArray($content);
    }
}
