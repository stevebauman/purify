<?php

namespace Stevebauman\Purify;

use HTMLPurifier_Config;
use HTMLPurifier_ConfigSchema;
use HTMLPurifier_DefinitionCache_Serializer;
use Laravel\Lumen\Application as Lumen;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application as Laravel;

class PurifyServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('purify', function ($app) {
            return new Purify(
                $app['config']['purify.settings'] ?? $this->getDefaultPurifierConfig()
            );
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
                __DIR__ . '/../config/purify.php' => config_path('purify.php'),
                $this->getDefaultPurifierDefinitionCacheDirectory() => storage_path('app/purify'),
            ], 'config');
        } elseif ($this->app instanceof Lumen) {
            $this->app->configure('purify');
        }
    }

    /**
     * Get the default HTML Purifier definition cache directory.
     *
     * @return string
     */
    protected function getDefaultPurifierDefinitionCacheDirectory()
    {
        $defaultConfig = HTMLPurifier_Config::create($this->getDefaultPurifierConfig());

        $serializer = new HTMLPurifier_DefinitionCache_Serializer(null);

        return $serializer->generateBaseDirectoryPath($defaultConfig);
    }

    /**
     * Get the default HTML Purifier configuration.
     *
     * @return array
     */
    protected function getDefaultPurifierConfig()
    {
        return HTMLPurifier_ConfigSchema::instance()->defaults;
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
