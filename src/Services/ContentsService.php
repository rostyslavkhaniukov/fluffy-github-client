<?php
declare(strict_types=1);

namespace Fluffy\GithubClient\Services;

use Fluffy\GithubClient\Entities\File;
use Fluffy\GithubClient\Entities\FilesystemEntity;
use Fluffy\GithubClient\Entities\Label;
use Fluffy\GithubClient\Http\Client as HttpClient;
use GuzzleHttp\RequestOptions;

/**
 * @package Fluffy\GithubClient\Services
 */
class ContentsService extends AbstractService
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
     * @param string $path
     * @param string|null $ref
     * @return File
     */
    public function read(string $owner, string $repository, string $path, ?string $ref = null): File
    {
        $options = [];
        if ($ref !== null) {
            $options = [
                RequestOptions::QUERY => [
                    'ref' => $ref,
                ],
            ];
        }

        $response = $this->client->get("/repos/{$owner}/{$repository}/contents/{$path}", $options);

        $content = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        return FilesystemEntity::fromArray($content);
    }

    public function getArchiveLink(string $owner, string $repository)
    {
        $response = $this->client->get("/repos/{$owner}/{$repository}/zipball");

        file_put_contents('1.zip', $response->getBody()->getContents());
    }
}
