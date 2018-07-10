<?php

namespace Tests;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Cesg\Auth\Provider\ApiUserProvider;

class ApiUserProviderTest extends TestCase
{
    /**
     * @test
     */
    public function auth_provider_is_instance_of_api_user()
    {
        config(['api-provider.bearer_token' => 'SOMEBEARERTOKEN']);
        $provider = Auth::guard()->getProvider();
        $this->assertInstanceOf(ApiUserProvider::class, $provider);
    }
}
