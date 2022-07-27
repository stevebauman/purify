<?php

namespace Stevebauman\Purify;

use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Filesystem\Filesystem;

class Purify
{
    /**
     * @var Filesystem
     */
    protected $files;

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
    public function __construct(Filesystem $files, array $config)
    {
        $this->files = $files;

        $this->purifier = new HTMLPurifier(
            HTMLPurifier_Config::create($config)
        );
    }

    /**
     * Sanitize the given input.
     *
     * @param array|string $input
     *
     * @return array|string
     */
    public function clean($input)
    {
        $this->ensureCacheSerializePathExists();

        return is_array($input)
            ? $this->purifier->purifyArray($input)
            : $this->purifier->purify($input);
    }

    protected function ensureCacheSerializePathExists()
    {
        $path = $this->purifier->config->get('Cache.SerializerPath');

        if (! $this->files->exists($path)) {
            $this->files->makeDirectory($path);
        }
    }
}
