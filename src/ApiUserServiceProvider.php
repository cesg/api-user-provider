<?php
class ApiUserServiceProvider extends Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app['auth']->provider('api-users', function ($app, $cofig) {
            return new ApiUserProvider();
        });
    }
}
