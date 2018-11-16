# Seeders 

Database seeders for our laravel applications. 

## Install 

This package is custom built for [this organization](https://github.com/nuclear-campain)!

But you can install the package via composer: 

```
$ composer require b61/seeders
```

For pulling the package in and further u need to install the [spatie/laravel-permission](https://github.com/spatie/laravel-permission) migrations from the package and migrate the database. You can do it with the following two commands: 

```
$ php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"
$ php artisan migrate
```

Don't forget to install `spatie/laravel-permission` scaffolding in your application. 

## Overview 

This package provides the base database seeders for our laravel applications. The `ActivismeBe\Seeders\DatabaseSeeder` class adds 
some extra utility to laravel's seeder. 

## Example 

```php 
use ActivismBe\Seeders\DatabaseSeeder as BaseDatabaseSeeder;
use ActivismBe\Seeders\AclTableSeeder;

class DatabaseSeeder extends BaseDatabaseSeeder
{
    /**
     * Handle the database seed. 
     *
     * @return void
     */
    public function run(): void 
    {
        parent::run();

        $this->call(AclTableSeeder::class);
    }
}
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email tim@activisme.be instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
