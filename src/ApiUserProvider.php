<?php

use GuzzleHttp\Client;

class ApiUserProvider implements Illuminate\Contracts\Auth\UserProvider
{
    protected $endponit;
    protected $headers;

    public function __construct($endponit, $headers) {
        $this->endpint = $endponit;
        $this->headers = $headers;
    }

    public function fetchUsers($credentials)
    {
        $client = new Client(array_merge([], $this->headers));

        $response = $client->get($this->endpint, [
            'query' => http_build_query($credentials)
        ]);

        $data = \GuzzleHttp\json_decode($response->getBody(), true);
        $userAtributes = array_key_exists('data', $data) ? $data['data'] : $data;
       
        return new User($userAtributes);
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