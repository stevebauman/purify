<?php

namespace Stevebauman\Purify;

use HTMLPurifier;
use HTMLPurifier_Config;

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
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->purifier = $this->makeNewHtmlPurifier($config);
    }

    /**
     * Sanitize the given input.
     *
     * @param array|string $input
     * @param array|null   $config
     *
     * @return array|string
     */
    public function clean($input, array $config = null)
    {
        return is_array($input)
            ? $this->purifier->purifyArray($input, $config)
            : $this->purifier->purify($input, $config);
    }

    /**
     * Set the underlying purifier instance.
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
     * Get the underlying purifier instance.
     *
     * @return HTMLPurifier
     */
    public function getPurifier()
    {
        return $this->purifier;
    }

    /**
     * Create a new HTMLPurifier instance.
     *
     * @param array $config
     *
     * @return HTMLPurifier
     */
    protected function makeNewHtmlPurifier(array $config = [])
    {
        return new HTMLPurifier(
            HTMLPurifier_Config::create($config)
        );
    }
}
