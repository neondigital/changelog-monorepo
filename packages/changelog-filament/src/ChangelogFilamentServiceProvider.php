<?php

namespace Neondigital\ChangelogFilament;

use Illuminate\Support\ServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ChangelogFilamentServiceProvider extends PackageServiceProvider
{
    public static string $name = 'changelog';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name);
    }

    public function register(): void
    {

    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'changelog');
    }
}
