<?php
namespace Cesg\Auth\Provider;

/**
 * Service Provider
 * @package Cesg/UserProvider
 */
class ApiUserServiceProvider extends Illuminate\Support\ServiceProvider
{
    /**
     * Register
     * @return void
     */
    public function register()
    {
        $this->app['auth']->provider(
            'api-users',
            function ($app, array $config) {
                return new ApiUserProvider($config['services.users'], $app['hash']);
            }
        );
    }
}
