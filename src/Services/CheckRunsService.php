<?php

declare(strict_types=1);

namespace Fluffy\GithubClient\Services;

use GuzzleHttp\RequestOptions;
use GuzzleHttp\Utils;

class CheckRunsService extends AbstractService
{
    public function create(string $repository, string $ref)
    {
        $response = $this->client->post("/repos/{$this->owner}/{$repository}/check-runs", [
            RequestOptions::JSON => [
                'name' => 'phpcs',
                'head_sha' => $ref,
            ],
            RequestOptions::HEADERS => [
                'Accept' => 'application/vnd.github.antiope-preview+json',
            ],
        ]);

        Utils::jsonDecode($response->getBody()->getContents(), true);
    }
}
