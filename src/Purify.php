<?php

namespace Stevebauman\Purify;

use HTMLPurifier;
use HTMLPurifier_Config;
use HTMLPurifier_ConfigSchema;

class Purify
{
    /**
     * The HTML Purifier instance.
     *
     * @var HTMLPurifier
     */
    protected $purifier;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $config = HTMLPurifier_Config::create($this->getSettings());

        $this->setPurifier(new HTMLPurifier($config));
    }

    /**
     * Cleans the specified input.
     *
     * If a configuration array is given, it **will not**
     * merge your current configuration.
     *
     * @param array|string $input
     * @param array|null   $config
     *
     * @return array|string
     */
    public function clean($input, array $config = null)
    {
        if (is_array($input)) {
            return $this->purifier->purifyArray($input, $config);
        } else {
            return $this->purifier->purify($input, $config);
        }
    }

    /**
     * Sets the current purifier to
     * the specified purifier object.
     *
     * @param HTMLPurifier $purifier
     *
     * @return $this
     */
    public function setPurifier(HTMLPurifier $purifier)
    {
        $this->purifier = $purifier;

        return $this;
    }

    /**
     * Returns the HTML purifier object.
     *
     * @return HTMLPurifier
     */
    public function getPurifier()
    {
        return $this->purifier;
    }

    /**
     * Returns the configuration settings for HTML Purifier.
     *
     * If no configuration settings are retrieved, a default
     * configuration schema is returned.
     *
     * @return array
     */
    public function getSettings()
    {
        $settings = config('purify.settings');

        if (is_array($settings) && count($settings) > 0) {
            // If the serializer path exists, we need to validate that
            // the folder actually exists, and create it if not
            if (array_key_exists('Cache.SerializerPath', $settings)) {
                $this->validateCachePath($settings['Cache.SerializerPath']);
            }

            return $settings;
        }

        return HTMLPurifier_ConfigSchema::instance()->defaults;
    }

    /**
     * Validates the HTML Purifiers cache path, and
     * creates the folder if it does not exist.
     *
     * @param string $path
     *
     * @return bool
     */
    protected function validateCachePath($path)
    {
        if (!is_dir($path)) {
            return mkdir($path);
        }

        return true;
    }
}
