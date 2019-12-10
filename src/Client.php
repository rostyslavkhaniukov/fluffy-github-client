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
    private $pullRequestsService = null;

    /** @var Services\WebhooksService */
    private $webhooksService = null;

    /** @var Services\LabelsService */
    private $labelsService = null;

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

    /**
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

        $content = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

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
        $content = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
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
