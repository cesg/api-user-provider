# Laravel Api User Provider

## Install

Add repository to composer.json file.

```
  "repositories": [
      {
        "type": "vcs",
        "url": "https://github.com/cesg/api-user-provider"
      }
    ]
```

Add composer dependecie
```
  composer require cesg/api-user-provider
```

## Configuration

publish the config file

```
  php artisan vendor:publish --provider="Cesg\Auth\Provider\ApiUserServiceProvider" --tag="config"
```

```php
return [
    'cache-key' => 'user-api-access-token',
    'uri' => env('', 'localhost/api/v1/users'),
    'headers' => [
    ]
];
```

Set the driver in auth config file.

```php
return [
    'providers' => [
              'users' => [
                  'driver' => 'api-users',
                  'model' => Cesg\Auth\Provider\User::class,
              ]
          ],
]
```

If you use laravel/passport need extend the Cesg\Auth\Provider\User and add the trait
Laravel\Passport\HasApiTokens and set the provider model to you own class.

```php
  class User extends \Cesg\Auth\Provider\User
  {
      use HasApiTokens;
  }
```
