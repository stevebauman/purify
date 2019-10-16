<?php

namespace Stevebauman\Purify;

use Laravel\Lumen\Application as Lumen;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application as Laravel;

class PurifyServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton('purify', function ($app) {
            return new Purify();
        });
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app instanceof Laravel && $this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/Config/purify.php' => config_path('purify.php'),], 'config');
        } elseif ($this->app instanceof Lumen) {
            $this->app->configure('purify');
        }
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
