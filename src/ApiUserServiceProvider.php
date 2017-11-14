<?php
namespace Cesg\Auth\Provider;

use Illuminate\Support\ServiceProvider;

/**
 * ApiUserServiceProvider
 * @package Cesg\Auth\Provider
 */
class ApiUserServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/api-provider.php' => config_path('api-provider.php'),
        ], 'config');
        $this->mergeConfigFrom(__DIR__.'/../config/api-provider.php', 'api-provider');
    }

    public function register()
    {
        $this->app['auth']->provider(
            'api-users',
            function ($app, array $config) {
                $appConfig = $app['config']['api-provider'];
                $appConfig['model'] = $config['model'];
                if ($app['cache']->has($appConfig['cache-key'])) {
                    $appConfig['headers'] = array_merge($appConfig['headers'], [
                       'Authorization' =>  $app['cache']->get($appConfig['cache-key'])
                    ]);
                }
                return new ApiUserProvider($appConfig, $app['hash'], $app['cache']);
            }
        );
    }
}
