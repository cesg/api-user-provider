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
        $this->publishes([
            __DIR__.'/../config/api-provider.php' => config_path('api-provider.php'),
        ], 'config');

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
        $this->mergeConfigFrom(__DIR__.'/../config/api-provider.php', 'api-provider');
    }

    private function addAuthorizationToken(array $appConfig)
    {
        if (!array_key_exists('bearer_token', $appConfig)) {
            throw new \Exception('Config file not contains bearer_token key.');
        }

        if (empty($appConfig['bearer_token'])) {
            throw new \Exception('Empty beare token in config.');
        }

        $token = $appConfig['bearer_token'];

        return array_merge($appConfig['headers'], [
            'Authorization' => "Bearer $token",
        ]);
    }
}
