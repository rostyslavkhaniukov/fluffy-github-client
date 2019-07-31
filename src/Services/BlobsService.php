<?php
declare(strict_types=1);

namespace Fluffy\GithubClient\Services;

use Fluffy\GithubClient\Entities\Branch;
use Fluffy\GithubClient\Entities\File;
use Fluffy\GithubClient\Entities\Git\Blob;
use Fluffy\GithubClient\Entities\Label;
use Fluffy\GithubClient\Http\Client as HttpClient;
use GuzzleHttp\RequestOptions;

/**
 * @package Fluffy\GithubClient\Services
 */
class BlobsService extends AbstractService
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
     * @param string $content
     * @param string $encoding
     * @return Blob
     */
    public function put(string $owner, string $repository, string $content, string $encoding): Blob
    {
        $response = $this->client->post("/repos/{$owner}/{$repository}/git/blobs", [
            RequestOptions::JSON => [
                'content' => $content,
                'encoding' => $encoding,
            ]
        ]);

        $content = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        return Blob::fromArray($content);
    }
}
