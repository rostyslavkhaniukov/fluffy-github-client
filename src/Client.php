<?php

declare(strict_types=1);

namespace Fluffy\GithubClient;

use Fluffy\GithubClient\Http;
use Fluffy\GithubClient\Services;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Utils;
use GuzzleHttp\Client as GuzzleClient;

class Client
{
    private string $endpoint;
    private string $owner;
    private Http\Client $httpClient;

    private ?Services\PullRequestsService $pullRequestsService = null;
    private ?Services\WebhooksService $webhooksService = null;
    private ?Services\LabelsService $labelsService = null;

    /** @var Services\CommitsService */
    private $commitsService = null;

    /** @var Services\StatusesService */
    private $statusesService = null;

    /** @var Services\CheckRunsService */
    private $checkRunsService = null;

    /** @var Services\WebhookProcessorService */
    private $webhooksProcessorService = null;

    /** @var Services\ContentsService */
    private $contentsService = null;

    /** @var Services\BranchesService */
    private $branchesService = null;

    /** @var Services\RefsService */
    private $refsService = null;

    /** @var Services\BlobsService */
    private $blobsService = null;

    /** @var Services\TreesService */
    private $treesService = null;

    /** @var Services\SearchService */
    private $searchService = null;

    /** @var Services\CompareService */
    private $compareService = null;

    /** @var Services\TagsService */
    private $tagsService = null;

    /** @var Services\ReleasesService */
    private $releasesService = null;

    /** @var Services\ActionsService */
    private $actionsService = null;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->endpoint = $config['endpoint'] ?? 'https://api.github.com';
        $this->owner = $config['owner'];
        $this->httpClient = $this->configureClient($this->endpoint, $config);
    }

    /**
     * @return Services\ActionsService
     */
    public function actionsService(): Services\ActionsService
    {
        if ($this->actionsService === null) {
            $this->actionsService = new Services\ActionsService($this->httpClient, $this->owner);
        }

        return $this->actionsService;
    }

    public function webhookProcessorService()
    {
        if ($this->webhooksProcessorService === null) {
            $this->webhooksProcessorService = new Services\WebhookProcessorService($this->httpClient, $this->owner);
        }

        return $this->webhooksProcessorService;
    }

    public function webhooks(): Services\WebhooksService
    {
        if ($this->webhooksService === null) {
            $this->webhooksService = new Services\WebhooksService($this->httpClient, $this->owner);
        }

        return $this->webhooksService;
    }

    public function compare(): Services\CompareService
    {
        if ($this->compareService === null) {
            $this->compareService = new Services\CompareService($this->httpClient);
        }

        return $this->compareService;
    }

    public function search(): Services\SearchService
    {
        if ($this->searchService === null) {
            $this->searchService = new Services\SearchService($this->httpClient);
        }

        return $this->searchService;
    }

    public function labels(): Services\LabelsService
    {
        if ($this->labelsService === null) {
            $this->labelsService = new Services\LabelsService($this->httpClient, $this->owner);
        }

        return $this->labelsService;
    }

    public function branches(): Services\BranchesService
    {
        if ($this->branchesService === null) {
            $this->branchesService = new Services\BranchesService($this->httpClient);
        }

        return $this->branchesService;
    }

    public function tags(): Services\TagsService
    {
        if ($this->tagsService === null) {
            $this->tagsService = new Services\TagsService($this->httpClient);
        }

        return $this->tagsService;
    }

    public function releases(): Services\ReleasesService
    {
        if ($this->releasesService === null) {
            $this->releasesService = new Services\ReleasesService($this->httpClient);
        }

        return $this->releasesService;
    }

    public function blobs(): Services\BlobsService
    {
        if ($this->blobsService === null) {
            $this->blobsService = new Services\BlobsService($this->httpClient);
        }

        return $this->blobsService;
    }

    public function refs(): Services\RefsService
    {
        if ($this->refsService === null) {
            $this->refsService = new Services\RefsService($this->httpClient);
        }

        return $this->refsService;
    }

    public function pullRequests(): Services\PullRequestsService
    {
        if ($this->pullRequestsService === null) {
            $this->pullRequestsService = new Services\PullRequestsService($this->httpClient, $this->owner);
        }

        return $this->pullRequestsService;
    }

    public function contents(): Services\ContentsService
    {
        if ($this->contentsService === null) {
            $this->contentsService = new Services\ContentsService($this->httpClient);
        }

        return $this->contentsService;
    }

    public function commits(): Services\CommitsService
    {
        if ($this->commitsService === null) {
            $this->commitsService = new Services\CommitsService($this->httpClient, $this->owner);
        }

        return $this->commitsService;
    }

    public function trees(): Services\TreesService
    {
        if ($this->treesService === null) {
            $this->treesService = new Services\TreesService($this->httpClient);
        }

        return $this->treesService;
    }

    public function statuses(): Services\StatusesService
    {
        if ($this->statusesService === null) {
            $this->statusesService = new Services\StatusesService($this->httpClient, $this->owner);
        }

        return $this->statusesService;
    }

    public function checkRuns(): Services\CheckRunsService
    {
        if ($this->checkRunsService === null) {
            $this->checkRunsService = new Services\CheckRunsService($this->httpClient, $this->owner);
        }

        return $this->checkRunsService;
    }

    /**
     * @param string $baseUri
     * @param array $config
     * @return Http\Client
     */
    public function configureClient(string $baseUri, array $config = []): Http\Client
    {
        $httpClient = new Http\Client(new GuzzleClient([
            'base_uri' => $this->prepareBaserUri($baseUri),
            'headers' => $this->prepareHeaders($config),
            'connect_timeout' => $config['connectTimeout'] ?? 30,
            'request_timeout' => $config['requestTimeout'] ?? 30,
        ]));

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
     * @param string $baseUri
     * @return string
     */
    private function prepareBaserUri(string $baseUri): string
    {
        return rtrim($baseUri, '/') . '/';
    }

    /**
     * @param string $url
     * @param array $query
     * @return mixed
     */
    public function get(string $url, $query = [])
    {
        $response = $this->httpClient->get($url, [
            RequestOptions::QUERY => $query,
        ]);

        $content = Utils::jsonDecode($response->getBody()->getContents(), true);

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
        $content = Utils::jsonDecode($response->getBody()->getContents(), true);
        return $content;
    }

    /**
     * @return string
     */
    public function getOwner(): string
    {
        return $this->owner;
    }
}
