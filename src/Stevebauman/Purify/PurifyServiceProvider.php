<?php

namespace Stevebauman\Purify;

use Stevebauman\Purify\Purify;
use Illuminate\Support\ServiceProvider;

class PurifyServiceProvider extends ServiceProvider
{
    /**
     * Stores the package configuration separator
     * for Laravel 5 compatibility
     *
     * @var string
     */
    public static $packageConfigSeparator = '::';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        /*
         * If the package method exists, we're using Laravel 4, if not, we're on 5
         */
        if (method_exists($this, 'package')) {
            $this->package('stevebauman/purify');
        } else {
            /*
             * Set the proper configuration separator since
             * retrieving configuration values in packages
             * changed from '::' to '.'
             */
            $this::$packageConfigSeparator = '.';

            /*
             * Assign the configuration as publishable, and tag it as 'config'
             */
            $this->publishes([
                __DIR__ . '../../../config/config.php' => config_path('purify.php'),
            ], 'config');
        }

        // Bind the new purify instance
        $this->app->bind('purify', function ($app) {
            return new Purify($app['config']);
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
