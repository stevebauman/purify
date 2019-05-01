<?php

namespace Stevebauman\Purify;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Application as Laravel;
use Laravel\Lumen\Application as Lumen;

class PurifyServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     */
    public function register()
    {
        if ($this->app instanceof Laravel && $this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/Config/purify.php' => config_path('purify.php'),], 'config');
        } elseif ($this->app instanceof Lumen) {
            $this->app->configure('purify');
        }

        // Bind the new purify instance.
        $this->app->singleton('purify', function ($app) {
            return new Purify();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['purify'];
    }
}
