<?php
declare(strict_types=1);

namespace Fluffy\GithubClient\Services;

use Fluffy\GithubClient\Entities\Issue;
use Fluffy\GithubClient\Http\Client as HttpClient;
use GuzzleHttp\RequestOptions;

/**
 * @package Fluffy\GithubClient\Services
 */
class SearchService extends AbstractService
{
    /**
     * @param HttpClient $client
     */
    public function __construct(HttpClient $client)
    {
        parent::__construct($client, '');
    }

    /**
     * @return Issue[]
     */
    public function issues(): array
    {
        $response = $this->client->get("/search/issues", [
            RequestOptions::QUERY => [
                'q' => 'is:open author:rostyslavkhaniukov',
            ],
        ]);

        $content = \GuzzleHttp\json_decode($response->getBody(), true);

        return Issue::fromCollection($content['items']);
    }
}
