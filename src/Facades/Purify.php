<?php

namespace Stevebauman\Purify\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Purify
 *
 * @static array|string clean($input, array $config = null)
 */
class Purify extends Facade
{
    /**
     * The facade accessor string.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'purify';
    }
}
