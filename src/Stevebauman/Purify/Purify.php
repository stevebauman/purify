<?php

namespace Stevebauman\Purify;

use Illuminate\Config\Repository;
use HTMLPurifier_ConfigSchema;
use HTMLPurifier_Config;
use HTMLPurifier;

/**
 * Class Purify
 * @package Stevebauman\Purify
 */
class Purify
{
    /**
     * The HTML Purifier instance.
     *
     * @var HTMLPurifier
     */
    protected $purifier;

    /**
     * The HTML Purifier configuration instance.
     *
     * @var HTMLPurifier_Config
     */
    protected $config;

    /**
     * The Laravel configuration repository instance.
     *
     * @var Repository
     */
    protected $repository;

    /**
     * Constructor.
     *
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->setRepository($repository);

        $configuration = HTMLPurifier_Config::create($this->getSettings());

        $this->setPurifierConfig($configuration);

        $this->setPurifier(new HTMLPurifier($this->config));
    }

    /**
     * Cleans the specified input.
     *
     * @param array|string $input
     * @param array $settings
     * @param bool $mergeSettings
     *
     * @return array|string
     */
    public function clean($input, $settings = [], $mergeSettings = true)
    {
        if($mergeSettings) $settings = $this->mergeSettings($settings);

        if(is_array($input)) {
            return $this->cleanArray($input, $settings);
        } else {
            return $this->purifier->purify($input, $settings);
        }
    }

    /**
     * Cleans the specified array of HTML input.
     *
     * @param array $input
     * @param array $settings
     * @param bool $mergeSettings
     *
     * @return array
     */
    public function cleanArray(array $input, $settings = [], $mergeSettings = true)
    {
        if($mergeSettings) $settings = $this->mergeSettings($settings);

        return $this->purifier->purifyArray($input, $settings);
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

    /**
     * Sets the current configuration repository.
     *
     * @param Repository $repository
     */
    private function setRepository(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Returns the configuration settings for HTML Purifier.
     *
     * If no configuration settings are retrieved, a default
     * configuration schema is returned.
     *
     * @return array
     */
    private function getSettings()
    {
        $settings = $this->repository->get('purify::settings.default');

        if(count($settings) > 0) return $settings;

        return HTMLPurifier_ConfigSchema::instance()->defaults;
    }

    /**
     * Merges the specified settings with the configuration settings.
     *
     * @param array $settings
     *
     * @return array
     */
    private function mergeSettings(array $settings = [])
    {
        return array_merge($settings, $this->getSettings());
    }
}

