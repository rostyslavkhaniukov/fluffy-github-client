<?php
declare(strict_types=1);

namespace Fluffy\GithubClient\Services;

use Fluffy\GithubClient\Entities\File;
use Fluffy\GithubClient\Entities\Directory;
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
        return File::fromArray($this->readPath($owner, $repository, $path, $ref));
    }

    /**
     * @param string $owner
     * @param string $repository
     * @param string $path
     * @param string|null $ref
     * @return Directory
     */
    public function readDirectory(string $owner, string $repository, string $path, ?string $ref = null): Directory
    {
        return Directory::fromArray($this->readPath($owner, $repository, $path, $ref));
    }

    /**
     * @param string $owner
     * @param string $repository
     * @param string $path
     * @param string|null $ref
     * @return array
     */
    private function readPath(string $owner, string $repository, string $path, ?string $ref = null): array
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

        return \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
    }

    public function getArchiveLink(string $owner, string $repository)
    {
        $response = $this->client->get("/repos/{$owner}/{$repository}/zipball");

        file_put_contents('1.zip', $response->getBody()->getContents());
    }
}
