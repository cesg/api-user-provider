<?php

namespace Cesg\Auth\Provider;

use Illuminate\Support\ServiceProvider;

/**
 * ApiUserServiceProvider
 */
class ApiUserServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app['auth']->provider(
            'api-users',
            function ($app, array $config) {
                $appConfig = $app['config']['api-provider'];
                $appConfig['model'] = $config['model'];
                $appConfig['headers'] = $this->addAuthorizationToken($appConfig);

                return new ApiUserProvider($appConfig, $app['hash'], $app['cache']);
            }
        );
    }

    public function register()
    {
        $this->publishes([
            __DIR__.'/../config/api-provider.php' => config_path('api-provider.php'),
        ], 'config');
        $this->mergeConfigFrom(__DIR__.'/../config/api-provider.php', 'api-provider');
    }

    private function addAuthorizationToken(array $appConfig)
    {
        $token = $this->app['cache']->get($appConfig['cache-key']);

        return array_merge($appConfig['headers'], [
            'Authorization' => "Bearer $token",
        ]);
    }
}
