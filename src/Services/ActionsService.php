<?php

declare(strict_types=1);

namespace Fluffy\GithubClient\Services;

use Fluffy\GithubClient\Entities\SecretsKey;
use Fluffy\GithubClient\Http\Client as HttpClient;
use GuzzleHttp\RequestOptions;

/**
 * @package Fluffy\GithubClient\Services
 */
class ActionsService extends AbstractService
{
    /**
     * @param HttpClient $client
     * @param string $owner
     */
    public function __construct(HttpClient $client, string $owner)
    {
        parent::__construct($client, $owner);
    }

    /**
     * @param string $owner
     * @param string $repository
     * @return SecretsKey
     */
    public function secrets(string $owner, string $repository): SecretsKey
    {
        $response = $this->client->get("/repos/{$owner}/{$repository}/actions/secrets/public-key");

        $content = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        return SecretsKey::fromArray($content);
    }

    /**
     * @param string $owner
     * @param string $repository
     * @param SecretsKey $key
     * @param string $name
     * @param string $value
     * @return bool
     * @throws \Exception
     */
    public function put(string $owner, string $repository, SecretsKey $key, string $name, string $value): bool
    {
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $encryptedValue = sodium_crypto_secretbox($value, $nonce, $key->getKey());

        $response = $this->client->put("/repos/{$owner}/{$repository}/actions/secrets/{$name}", [
            RequestOptions::JSON => [
                'encrypted_value' => $encryptedValue,
                'key_id' => $key->getKeyId(),
            ]
        ]);

        return $response->getStatusCode() === 201 || $response->getStatusCode() === 204;
    }
}