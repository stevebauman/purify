<?php

namespace Stevebauman\Purify;

use Illuminate\Support\ServiceProvider;

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
        $this->publishes([
            __DIR__.'/Config/config.php' => config_path('purify.php'),
        ], 'config');

        // Bind the new purify instance
        $this->app->bind('purify', function ($app) {
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
