<?php

namespace Stevebauman\Purify\Cache;

use HTMLPurifier_DefinitionCache;
use Illuminate\Support\Facades\Cache;

class CacheDefinitionCache extends HTMLPurifier_DefinitionCache
{
    /**
     * The cache repository.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * Constructor.
     *
     * @param string $type
     */
    public function __construct($type)
    {
        parent::__construct($type);

        $this->cache = Cache::driver(
            config('purify.serializer.driver')
        );
    }

    /**
     * Adds a definition object to the cache.
     *
     * @param \HTMLPurifier_Definition $def
     * @param \HTMLPurifier_Config     $config
     *
     * @return bool|void
     */
    public function add($def, $config)
    {
        if (! $this->checkDefType($def)) {
            return;
        }

        $key = $this->generateKey($config);

        if ($this->cache->has($key)) {
            return false;
        }

        return $this->cache->put($key, serialize($def));
    }

    /**
     * Unconditionally saves a definition object to the cache.
     *
     * @param \HTMLPurifier_Definition $def
     * @param \HTMLPurifier_Config     $config
     *
     * @return bool|void
     */
    public function set($def, $config)
    {
        if (! $this->checkDefType($def)) {
            return;
        }

        $key = $this->generateKey($config);

        return $this->cache->put($key, serialize($def));
    }

    /**
     * Replace an object in the cache.
     *
     * @param \HTMLPurifier_Definition $def
     * @param \HTMLPurifier_Config     $config
     *
     * @return bool|void
     */
    public function replace($def, $config)
    {
        if (! $this->checkDefType($def)) {
            return;
        }

        $key = $this->generateKey($config);

        if (! $this->cache->has($key)) {
            return false;
        }

        return $this->cache->put($key, serialize($def));
    }

    /**
     * Retrieves a definition object from the cache.
     *
     * @param \HTMLPurifier_Config $config
     *
     * @return bool|\HTMLPurifier_Config
     */
    public function get($config)
    {
        $key = $this->generateKey($config);

        if (! $this->cache->has($key)) {
            return false;
        }

        return unserialize($this->cache->get($key));
    }

    /**
     * Removes a definition object to the cache.
     *
     * @param \HTMLPurifier_Config $config
     *
     * @return bool
     */
    public function remove($config)
    {
        $key = $this->generateKey($config);

        if (! $this->cache->has($key)) {
            return false;
        }

        return $this->cache->delete($key);
    }

    /**
     * Clears all objects from cache.
     *
     * @param \HTMLPurifier_Config $config
     *
     * @return bool
     */
    public function flush($config)
    {
        return $this->cache->clear();
    }

    /**
     * Clears all expired (older version or revision) objects from cache.
     *
     * @param \HTMLPurifier_Config $config
     *
     * @return bool
     */
    public function cleanup($config)
    {
        $key = $this->generateKey($config);

        if ($this->isOld($key, $config)) {
            return $this->cache->delete($key);
        }

        return true;
    }
}
