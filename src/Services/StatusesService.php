<?php

declare(strict_types=1);

namespace Fluffy\GithubClient\Services;

use Fluffy\GithubClient\Entities\Label;
use Fluffy\GithubClient\Entities\Status;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Utils;

class StatusesService extends AbstractService
{
    public function all(string $repository, string $ref): array
    {
        $response = $this->client->get("/repos/{$this->owner}/{$repository}/commits/{$ref}/statuses");

        $content = Utils::jsonDecode($response->getBody()->getContents(), true);

        return Status::fromCollection($content);
    }

    public function create(string $repository, string $ref)
    {
        $response = $this->client->post("/repos/{$this->owner}/{$repository}/statuses/{$ref}", [
            RequestOptions::JSON => [
                'state' => 'success',
                'context' => 'ci/circleci: Build Error',
                'node_id' => 'MDEzOlN0YXR1c0NvbnRleHQ2NTMxNzU4MzQy',
            ]
        ]);

        Utils::jsonDecode($response->getBody()->getContents(), true);
    }
}
