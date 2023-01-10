<?php

namespace Stevebauman\Purify;

use HTMLPurifier_Config;
use Illuminate\Support\Manager;
use InvalidArgumentException;
use Stevebauman\Purify\Definitions\Definition;

class PurifyManager extends Manager
{
    /**
     * Convenience alias for driver().
     *
     * @param string|array|null $config
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function config($config = null)
    {
        return $this->driver($config);
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->container->make('config')->get('purify.default');
    }

    /**
     * Get a driver instance.
     *
     * @param string|array|null $driver
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function driver($driver = null)
    {
        // First, we will check if the provided "driver" is an array. If so,
        // we're dealing with an inline defined config. We'll serialize it
        // into a string to dynamically define and set its configuration.
        if (is_array($driver)) {
            $config = $driver;
            $driver = md5(serialize($driver));

            $this->container->make('config')->set("purify.configs.{$driver}", $config);
        }

        return parent::driver($driver);
    }

    /**
     * Create a new driver instance.
     *
     * @param string|array $driver
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    protected function createDriver($driver)
    {
        // First, we will determine if a custom driver creator exists for the given driver and
        // if it does not we will check for a creator method for the driver. Custom creator
        // callbacks allow developers to build their own "drivers" easily using Closures.
        if (isset($this->customCreators[$driver])) {
            return $this->callCustomCreator($driver);
        }

        if ($config = $this->resolveConfig($driver)) {
            return $this->createInstance($driver, $config);
        }

        throw new InvalidArgumentException("Purify config [$driver] not defined.");
    }

    /**
     * Resolve the configuration for the given config name.
     *
     * @param string $name
     *
     * @return array
     */
    protected function resolveConfig($name)
    {
        return $this->container->make('config')->get("purify.configs.{$name}");
    }

    /**
     * Resolve the serializer filepath the given config name.
     *
     * @param string $name
     *
     * @return string|false
     */
    protected function resolveSerializerPath($name)
    {
        $path = $this->container->make('config')->get('purify.serializer');

        if (empty($path)) {
            return false;
        }

        return implode(DIRECTORY_SEPARATOR, [$path, $name]);
    }

    /**
     * Create a new Purify instance with the given config.
     *
     * @param string $name
     * @param array  $config
     *
     * @return Purify
     */
    protected function createInstance(string $name, array $config)
    {
        $serializerPath = $this->resolveSerializerPath($name);

        if (! empty($serializerPath) && ! is_dir($serializerPath)) {
            mkdir($serializerPath, 0755, true);
        }

        return new Purify(
            $this->createHtmlConfig(array_merge(array_filter([
                'Cache.SerializerPath' => $serializerPath,
            ]), $config))
        );
    }

    /**
     * Create an HTML purifier configuration instance.
     *
     * @param array $config
     *
     * @return HTMLPurifier_Config
     */
    protected function createHtmlConfig($config)
    {
        $htmlConfig = HTMLPurifier_Config::create($config);

        $htmlConfig->set('HTML.DefinitionID', 'HTML-purify');
        $htmlConfig->set('HTML.DefinitionRev', 1);

        // If no cache serializer path is set, we will assume
        // that caching has been intentionally disabled and
        // prevent attempts to save to a null directory.
        if (empty($config['Cache.SerializerPath'])) {
            $htmlConfig->set('Cache.DefinitionImpl', null);
        }

        if ($definition = $htmlConfig->maybeGetRawHTMLDefinition()) {
            $definitionsClass = $this->container->make('config')->get('purify.definitions');

            if ($definitionsClass && is_a($definitionsClass, Definition::class, true)) {
                $definitionsClass::apply($definition);
            }
        }

        return $htmlConfig;
    }
}
