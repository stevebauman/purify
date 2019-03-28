<?php

namespace Stevebauman\Purify;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;
use Laravel\Lumen\Application as LumenApplication;

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
        $this->setupConfig($this->app);

        // Bind the new purify instance
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

    protected function setupConfig(Container $app)
    {
        if ($app instanceof LaravelApplication && $app->runningInConsole()) {
            $this->publishes([__DIR__ . '/Config/purify.php' => config_path('purify.php'),], 'config');
        } elseif ($app instanceof LumenApplication) {
            $app->configure('purify');
        }
    }
}
