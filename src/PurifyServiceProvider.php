<?php

namespace Stevebauman\Purify;

use Illuminate\Foundation\Application as Laravel;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as Lumen;

class PurifyServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/purify.php', 'purify');

        $this->app->singleton('purify', function ($app) {
            return new PurifyManager($app);
        });
    }

    /**
     * Register the publishable configuration.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app instanceof Laravel && $this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/purify.php' => config_path('purify.php'),
            ], 'config');
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
