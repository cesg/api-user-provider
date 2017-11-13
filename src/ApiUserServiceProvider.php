<?php
class ApiUserServiceProvider extends Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app['auth']->provider('api-users', function ($app, array $config) {
            return new ApiUserProvider($config['services.users'], $app['hash']);
        });
    }
}
