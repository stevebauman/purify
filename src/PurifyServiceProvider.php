<?php

namespace Stevebauman\Purify;

use Illuminate\Support\ServiceProvider;

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
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/purify.php' => config_path('purify.php'),
            ], 'config');
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
