<?php

namespace Neondigital\Changelog;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Neondigital\Changelog\Console\Commands\ReleaseCommand;

class ChangelogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/changelog.php', 'changelog');


        $this->app->singleton('changelog', function ($app) {
            return new Changelog;
        });
    }

    public function boot()
    {
        $this->commands([
            ReleaseCommand::class,
        ]);
        $this->loadViewComponentsAs('neon', [
            \Neondigital\Changelog\View\Changelog::class,
        ]);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'changelog');

        Blade::directive('changelogStyles', function () {
            $contents = file_get_contents(__DIR__.'/../resources/dist/changelog.css');
            return '<style>'.$contents.'</style>';
        });
    }
}
