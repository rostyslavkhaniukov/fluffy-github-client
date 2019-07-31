<?php
declare(strict_types=1);

namespace Fluffy\GithubClient;

use Fluffy\GithubClient\Http;
use Fluffy\GithubClient\Entities;
use Fluffy\GithubClient\Services;
use GuzzleHttp\RequestOptions;

/**
 * Class Client
 * @package Fluffy\GithubClient
 */
class Client
{
    /** @var string */
    private $endpoint;

    /** @var string */
    private $owner;

    /** @var string */
    private $repo;

    /** @var Http\Client */
    private $httpClient;

    /** @var Services\PullRequestsService */
    private $pullRequestsService;

    /** @var Services\WebhooksService */
    private $webhooksService;

    /** @var Services\LabelsService */
    private $labelsService;

    /** @var Services\CommitsService */
    private $commitsService;

    /** @var Services\StatusesService */
    private $statusesService;

    /** @var Services\CheckRunsService */
    private $checkRunsService;

    /** @var Services\WebhookProcessorService */
    private $webhooksProcessorService;

    /** @var Services\ContentsService */
    private $contentsService;

    /** @var Services\BranchesService */
    private $branchesService;

    /** @var Services\RefsService */
    private $refsService;

    /** @var Services\BlobsService */
    private $blobsService;

    /** @var Services\TreesService */
    private $treesService;

    /** @var Services\SearchService */
    private $searchService;

    /**
     * Client constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->endpoint = $config['endpoint'] ?? 'https://api.github.com';
        $this->owner = $config['owner'];
        $this->httpClient = $this->configureClient($this->endpoint, $config);
    }

    public function webhookProcessorService()
    {
        if (!$this->webhooksProcessorService) {
            $this->webhooksProcessorService = new Services\WebhookProcessorService($this->httpClient, $this->owner);
        }

        return $this->webhooksProcessorService;
    }

    public function webhooks(): Services\WebhooksService
    {
        if (!$this->webhooksService) {
            $this->webhooksService = new Services\WebhooksService($this->httpClient, $this->owner);
        }

        return $this->webhooksService;
    }

    public function search(): Services\SearchService
    {
        if (!$this->searchService) {
            $this->searchService = new Services\SearchService($this->httpClient, $this->owner);
        }

        return $this->searchService;
    }

    public function labels(): Services\LabelsService
    {
        if (!$this->labelsService) {
            $this->labelsService = new Services\LabelsService($this->httpClient, $this->owner);
        }

        return $this->labelsService;
    }

    public function branches(): Services\BranchesService
    {
        if (!$this->branchesService) {
            $this->branchesService = new Services\BranchesService($this->httpClient);
        }

        return $this->branchesService;
    }

    public function blobs(): Services\BlobsService
    {
        if (!$this->blobsService) {
            $this->blobsService = new Services\BlobsService($this->httpClient);
        }

        return $this->blobsService;
    }

    public function refs(): Services\RefsService
    {
        if (!$this->refsService) {
            $this->refsService = new Services\RefsService($this->httpClient);
        }

        return $this->refsService;
    }

    public function pullRequests(): Services\PullRequestsService
    {
        if (!$this->pullRequestsService) {
            $this->pullRequestsService = new Services\PullRequestsService($this->httpClient, $this->owner);
        }

        return $this->pullRequestsService;
    }

    public function contents(): Services\ContentsService
    {
        if (!$this->contentsService) {
            $this->contentsService = new Services\ContentsService($this->httpClient);
        }

        return $this->contentsService;
    }

    public function commits(): Services\CommitsService
    {
        if (!$this->commitsService) {
            $this->commitsService = new Services\CommitsService($this->httpClient, $this->owner);
        }

        return $this->commitsService;
    }

    public function trees(): Services\TreesService
    {
        if (!$this->treesService) {
            $this->treesService = new Services\TreesService($this->httpClient);
        }

        return $this->treesService;
    }

    public function statuses(): Services\StatusesService
    {
        if (!$this->statusesService) {
            $this->statusesService = new Services\StatusesService($this->httpClient, $this->owner);
        }

        return $this->statusesService;
    }

    public function checkRuns(): Services\CheckRunsService
    {
        if (!$this->checkRunsService) {
            $this->checkRunsService = new Services\CheckRunsService($this->httpClient, $this->owner);
        }

        return $this->checkRunsService;
    }

    /**
     * @param $baseUri
     * @param array $config
     * @return Http\Client
     */
    public function configureClient($baseUri, array $config = []): Http\Client
    {
        $httpClient = new Http\Client([
            'base_uri' => $this->prepareBaserUri($baseUri),
            'headers' => $this->prepareHeaders($config),
            'connect_timeout' => $config['connectTimeout'] ?? 30,
            'request_timeout' => $config['requestTimeout'] ?? 30,
        ]);

        return $httpClient;
    }

    /**
     * @param array $config
     * @return array
     */
    private function getDefaultHeaders(array $config): array
    {
        return [
            'Authorization' => 'token ' . ($config['token'] ?? ''),
            'User-Agent' => 'rostyslavkhaniukov/fluffy-github-client',
        ];
    }
    /**
     * @param array $config
     * @return array
     */
    private function prepareHeaders(array $config): array
    {
        return array_merge(
            $this->getDefaultHeaders($config),
            $config['headers'] ?? []
        );
    }

    /**
     * @param $baseUri
     * @return string
     */
    private function prepareBaserUri(string $baseUri): string
    {
        return rtrim($baseUri, '/') . '/';
    }

    public function getPRCommits()
    {
        return $this->get(
            $this->pullRequestEndpoint() . '/' . '1' . '/commits'
        );
    }

    public function collectReleasePRs()
    {
        $releases = $this->collectReleases();
        $pullRequests = $this->pullRequests()->closed()->all($this->repo);
        var_dump(count($pullRequests));
        $diff = $this->collectDiff($releases[2]);


        $shas = array_map(function ($commit) {
            return $commit->sha;
        }, $diff->commits);

        $releasePR = [];
        foreach ($pullRequests as $pullRequest) {
            /** @var Entities\PullRequest $pullRequest */
            if (in_array($pullRequest->head->sha, $shas, true)
                || in_array($pullRequest->mergeCommitSha, $shas, true)
            ) {
                $releasePR[] = $pullRequest;
            }
        }

        return $releasePR;
    }

    public function collectReleasePRs2()
    {
        $releases = Entities\Release::fromCollection($this->collectReleases());
        $tag = $releases[1]->tagName;

        $diff = $this->get($this->compareEndpoint($tag, 'master'));
        $diff = Entities\Diff::fromArray($diff);
        $shas = array_map(function ($commit) {
            return $commit->sha;
        }, $diff->commits);

        $pullRequests = $this->get($this->pullRequestEndpoint(), [
            'state' => 'closed',
            'base' => 'master',
            'per_page' => 100,
            'sort' => 'updated',
            'direction' => 'desc'
        ]);

        $releasePRs = [];
        $pullRequests = Entities\PullRequest::fromCollection($pullRequests);
        foreach ($pullRequests as $pullRequest) {
            if (in_array($pullRequest->mergeCommitSha, $shas, true)
                || in_array($pullRequest->head->sha, $shas, true)) {
                $releasePRs[] = $pullRequest;
            }
        }
        array_walk($releasePRs, function ($item) {
            var_dump($item->title);
        });
        die;
    }

    /**
     * @return array
     */
    public function collectReleases()
    {
        print("Collect releases\n");
        return Entities\Release::fromCollection($this->get($this->releasesEndpoint()));
    }

    /**
     * @param Entities\Release $release
     * @return Entities\Diff
     */
    public function collectDiff(Entities\Release $release)
    {
        $diff = $this->get($this->compareEndpoint($release, 'master'));
        return Entities\Diff::fromArray($diff);
    }

    /**
     * @param $url
     * @param array $query
     * @return mixed
     */
    public function get($url, $query = [])
    {
        $response = $this->httpClient->get($url, [
            RequestOptions::QUERY => $query,
        ]);
        $content = \GuzzleHttp\json_decode($response->getBody(), true);
        return $content;
    }

    public function post($url)
    {
        $response = $this->httpClient->post($url, [
            RequestOptions::JSON => [
                'title' => 'Preparing release pull request...',
                'head' => 'develop',
                'base' => 'master',
            ],
        ]);
        $content = \GuzzleHttp\json_decode($response->getBody(), true);
        return $content;
    }

    public function getLastCommit()
    {
        $commits = $this->get($this->commitsEndpoint(), [
            'per_page' => 1,
        ]);
        $commits = Entities\Commit::fromCollection($commits);
        return array_shift($commits);
    }

    /**
     * @param $sha
     */
    public function createTag($sha)
    {
        $tag = $this->post($this->tagsEndpoint(), [
            'tag' => 'v0.0.1',
            'message' => 'Test',
            'type' => 'commit',
            'object' => $sha,
        ]);
        var_dump($tag);
    }
}
