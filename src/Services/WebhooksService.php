<?php
declare(strict_types=1);

namespace Fluffy\GithubClient\Services;

use Fluffy\GithubClient\Entities\Webhook;
use Fluffy\GithubClient\Http\Client as HttpClient;
use GuzzleHttp\RequestOptions;

/**
 * Class WebhooksService
 * @package Fluffy\GithubClient\Services
 */
class WebhooksService extends AbstractService
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
     * @return Webhook[]
     */
    public function all(string $repository): array
    {
        $response = $this->client->get("/repos/{$this->owner}/{$repository}/hooks");

        $content = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        return Webhook::fromCollection($content);
    }

    /**
     * @param string $repository
     * @return \Fluffy\GithubClient\Entities\Release|Webhook
     */
    public function create(string $repository)
    {
        $response = $this->client->post("/repos/{$this->owner}/{$repository}/hooks", [
            RequestOptions::JSON => [
                'type' => 'web',
                'config' => [
                    'url' => 'http://localhost:8099/hook',
                ],
            ]
        ]);

        $content = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        return Webhook::fromArray($content);
    }
}
