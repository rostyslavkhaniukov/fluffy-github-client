<?php

declare(strict_types=1);

namespace Fluffy\GithubClient\Services;

use Fluffy\GithubClient\Entities\Commit;
use Fluffy\GithubClient\Entities\Git;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Utils;

class CommitsService extends AbstractService
{
    public function checkSuites(string $repository, string $ref)
    {
        $response = $this->client->get("/repos/{$this->owner}/{$repository}/commits/{$ref}/check-suites", [
            RequestOptions::HEADERS => [
                'Accept' => 'application/vnd.github.antiope-preview+json',
            ]
        ]);

        Utils::jsonDecode($response->getBody()->getContents(), true);
    }

    public function get(string $repository, string $sha): Commit
    {
        $response = $this->client->get("/repos/{$this->owner}/{$repository}/commits/{$sha}");

        $content = Utils::jsonDecode($response->getBody()->getContents(), true);

        return Commit::fromArray($content);
    }

    public function commit(
        string $owner,
        string $repository,
        string $treeSha,
        array $parents,
        string $message
    ): Git\Commit {
        $response = $this->client->post("/repos/{$owner}/{$repository}/git/commits", [
            RequestOptions::JSON => [
                'message' => $message,
                'tree' => $treeSha,
                'parents' => $parents
            ]
        ]);

        $content = Utils::jsonDecode($response->getBody()->getContents(), true);

        return Git\Commit::fromArray($content);
    }
}
