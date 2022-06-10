<?php

declare(strict_types=1);

namespace Fluffy\GithubClient\Http;

use Fluffy\GithubClient\Exceptions\DomainException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Client as GuzzleClient;

class Client
{
    private array $queryParams = [];
    private GuzzleClient $guzzleClient;

    public function __construct(GuzzleClient $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }

    /**
     * @inheritdoc
     * @throws DomainException
     */
    public function request($method, $uri = '', array $options = [])
    {
        try {
            $resolvedOptions = $this->resolveOptions($options);

            $this->clearOptions();

            $response = $this->guzzleClient->request($method, $uri, $resolvedOptions);
        } catch (RequestException $exception) {
            $code = $exception->getCode();
            if ($exception->hasResponse() && $exception->getResponse() !== null) {
                $code = $exception->getResponse()->getStatusCode();
            }
            throw new DomainException($exception->getMessage(), $code, $exception);
        } catch (\Exception $exception) {
            throw new DomainException($exception->getMessage(), 0, $exception);
        }

        return $response;
    }

    public function get($uri = '', array $options = [])
    {
        return $this->request('GET', $uri, $options);
    }

    public function post($uri = '', array $options = [])
    {
        return $this->request('POST', $uri, $options);
    }

    public function patch($uri = '', array $options = [])
    {
        return $this->request('PATCH', $uri, $options);
    }

    public function put($uri = '', array $options = [])
    {
        return $this->request('PUT', $uri, $options);
    }

    /**
     * @param string $key
     * @param string[] $values
     * @return Client
     */
    public function setQueryParam(string $key, array $values): Client
    {
        $values = implode(',', $values);
        $this->queryParams[$key] = $values;

        return $this;
    }

    /**
     * @param array $params
     * @return Client
     */
    public function setQueryParams(array $params): Client
    {
        $this->queryParams = array_merge($this->queryParams, $params);

        return $this;
    }

    /**
     * @param array $options
     * @return array
     */
    private function resolveOptions(array $options): array
    {
        if (!empty($this->queryParams)) {
            foreach ($this->queryParams as $param => $value) {
                $options[RequestOptions::QUERY][$param] = $value;
            }
        }

        return $options;
    }

    private function clearOptions(): void
    {
        $this->queryParams = [];
    }
}
