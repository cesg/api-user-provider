<?php

use GuzzleHttp\Client;
use Illuminate\Contracts\Auth\Authenticatable;

class ApiUserProvider implements Illuminate\Contracts\Auth\UserProvider
{
    protected $uri;
    protected $headers;
    protected $hash;

    public function __construct($config, $hash) {
        $this->uri = $config['uri'];
        $this->headers = $config['headers'];
        $this->hash = $hash;
    }

    public function fetchUsers($credentials)
    {
        $client = new Client(array_merge([], $this->headers));

        $response = $client->get($this->uri, [
            'query' => http_build_query($credentials)
        ]);

        $data = \GuzzleHttp\json_decode($response->getBody(), true);
        $data = array_key_exists('data', $data) ? $data['data'] : $data;
        return new User(array_shift($data));
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed   $identifier
     * @param  string  $token
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
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        $user = $this->fetchUsers($credentials);
        return $user;
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
    }
}