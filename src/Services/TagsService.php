<?php
declare(strict_types=1);

namespace Fluffy\GithubClient\Services;

use Fluffy\GithubClient\Entities\Tag;
use Fluffy\GithubClient\Http\Client as HttpClient;
use GuzzleHttp\RequestOptions;

/**
 * @package Fluffy\GithubClient\Services
 */
class TagsService extends AbstractService
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
     * @return Tag[]
     */
    public function all(string $owner, string $repository): array
    {
        $response = $this->client->get("/repos/{$owner}/{$repository}/tags");

        $content = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        return Tag::fromCollection($content);
    }

    /**
     * @param string $owner
     * @param string $repository
     * @param string $targetCommitSHA
     * @param string $tagName
     * @param string $tagMessage
     * @return Tag
     */
    public function create(
        string $owner,
        string $repository,
        string $targetCommitSHA,
        string $tagName,
        string $tagMessage = ''
    ): Tag {
        $response = $this->client->post("/repos/{$owner}/{$repository}/git/tags", [
            RequestOptions::JSON => [
                'tag' => $tagName,
                'message' => $tagMessage,
                'object' => $targetCommitSHA,
                'type' => 'commit',
            ]
        ]);

        $content = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        return Tag::fromArray($content);
    }
}
