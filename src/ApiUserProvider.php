<?php

namespace Cesg\Auth\Provider;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Hashing\Hasher;

class ApiUserProvider implements UserProvider
{
    protected $uri;
    protected $headers = [
        'Accept' => 'aplication/json',
    ];
    protected $hash;
    protected $model;
    protected $cache;
    protected $cacheTtl;

    public function __construct(array $config, Hasher $hash, $cache)
    {
        $this->uri = $config['uri'];
        $this->headers = array_merge($this->headers, $config['headers']);
        $this->hash = $hash;
        $this->model = $config['model'];
        $this->cache = $cache;
        $this->cacheTtl = array_key_exists('cache_ttl', $config) ? $config['cache_ttl'] : 10;
    }

    /**
     * @param $credentials
     *
     * @return Authenticatable
     */
    public function fetchUsers($credentials)
    {
        $client = new Client([
            'allow_redirects' => false,
            'headers' => $this->headers,
        ]);

        try {
            $response = $client->get(
                $this->uri,
                ['query' => http_build_query($credentials)]
            );
        } catch (ClientException $exception) {
            logger()->critical('Api provider: '.$exception->getMessage(), $exception->getTrace());

            return null;
        }

        $data = \GuzzleHttp\json_decode($response->getBody(), true);
        $data = array_key_exists('data', $data) ? $data['data'] : $data;

        return new $this->model(array_shift($data));
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        return $this->cache->remember("api-users:users,identifier=$identifier", $this->cacheTtl, function () use ($identifier) {
            return $this->fetchUsers(['id' => $identifier]);
        });
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed   $identifier
     * @param  string  $token
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string  $token
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        $key = http_build_query($credentials);
        $user = $this->cache->remember("user-provider:$key", 1, function () use ($credentials) {
            return $this->fetchUsers($credentials);
        });

        return $user;
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     *
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return $this->hash->check($credentials['password'], $user->getAuthPassword());
    }
}
