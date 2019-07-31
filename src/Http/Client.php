<?php
declare(strict_types=1);

namespace Fluffy\GithubClient\Http;

use Fluffy\GithubClient\Exceptions\DomainException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;

/**
 * Class Client
 * @package Fluffy\GithubClient\Http
 */
class Client extends \GuzzleHttp\Client
{
    /**
     * @var array
     */
    private $queryParams = [];

    /**
     * @inheritdoc
     * @throws DomainException
     */
    public function request($method, $uri = '', array $options = [])
    {
        try {
            $resolvedOptions = $this->resolveOptions($options);

            $this->clearOptions();

            $response = parent::request($method, $uri, $resolvedOptions);
        } catch (RequestException $exception) {
            $code = $exception->getCode();
            if ($exception->hasResponse() && $exception->getResponse() !== null) {
                $code = $exception->getResponse()->getStatusCode();
            }
            throw new DomainException($exception->getMessage(), $code);
        } catch (\Exception $exception) {
            throw new DomainException($exception->getMessage());
        }

        return $response;
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
