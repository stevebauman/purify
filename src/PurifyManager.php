<?php

namespace Stevebauman\Purify;

use Illuminate\Support\Manager;
use InvalidArgumentException;

class PurifyManager extends Manager
{
    /**
     * Convenience alias for driver().
     *
     * @param string|array|null $config
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
        return $this->config->get('purify.default');
    }

    /**
     * Get a driver instance.
     *
     * @param string|array|null $driver
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function driver($driver = null)
    {
        // First, we will check if the provided "driver" is an array.
        // If so, we're dealing with inline defined config.
        if (is_array($driver)) {
            $config = $driver;
            $driver = md5(serialize($driver));

            $this->config->set("purify.configs.{$driver}", $config);
        }

        return parent::driver($driver);
    }

    /**
     * Create a new driver instance.
     *
     * @param string|array $driver
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
     * @return array
     */
    protected function resolveConfig($name)
    {
        return $this->config->get("purify.configs.{$name}");
    }

    /**
     * Create a new Purify instance with the given config.
     *
     * @param string $name
     * @param array $config
     * @return Purify
     */
    protected function createInstance(string $name, array $config)
    {
        $filesystem = $this->container->make('filesystem')->disk(
            $this->config->get('purify.serializer.disk', 'local')
        );

        $path = $this->config->get('purify.serializer.path') . DIRECTORY_SEPARATOR . $name;

        return new Purify($filesystem, array_merge([
            'Cache.SerializerPath' => $path,
        ], $config));
    }
}