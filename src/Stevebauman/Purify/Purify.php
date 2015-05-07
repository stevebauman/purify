<?php

namespace Stevebauman\Purify;

use Illuminate\Config\Repository;
use HTMLPurifier_Config;
use HTMLPurifier;

/**
 * Class Purify
 * @package Stevebauman\Purify
 */
class Purify
{
    /**
     * @var HTMLPurifier
     */
    protected $purifier;

    /**
     * @var HTMLPurifier_Config
     */
    protected $config;

    /**
     * Constructor.
     *
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->setPurifierConfig(HTMLPurifier_Config::createDefault());

        $configuration = $config->get('purify::config');

        $this->config->loadArray($configuration);

        $this->setPurifier(new HTMLPurifier($this->config));
    }

    /**
     * Cleans the specified input.
     *
     * @param array|string $input
     * @param array $config
     *
     * @return array|string
     */
    public function clean($input, $config = null)
    {
        if(is_array($input)) {
            return $this->cleanArray($input, $config);
        } else {
            return $this->purifier->purify($input, $config);
        }
    }

    /**
     * Cleans the specified array of HTML input.
     *
     * @param array $input
     * @param array $config
     *
     * @return array
     */
    public function cleanArray(array $input, $config = null)
    {
        return $this->purifier->purifyArray($input, $config);
    }

    /**
     * Sets the current purifier to
     * the specified purifier object.
     *
     * @param HTMLPurifier $purifier
     */
    public function setPurifier(HTMLPurifier $purifier)
    {
        $this->purifier = $purifier;
    }

    /**
     * Returns the current purifier object.
     *
     * @return HTMLPurifier
     */
    public function getPurifier()
    {
        return $this->purifier;
    }

    /**
     * Sets the current purifiers configuration object.
     *
     * @param HTMLPurifier_Config $configuration
     */
    public function setPurifierConfig(HTMLPurifier_Config $configuration)
    {
        $this->config = $configuration;
    }

    /**
     * Returns the HTML Purifiers configuration object.
     *
     * @return HTMLPurifier_Config
     */
    public function getPurifierConfig()
    {
        return $this->config;
    }
}
