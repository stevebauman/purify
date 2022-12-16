<?php

namespace Stevebauman\Purify\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array|string clean(array|string $input, array $config = null)
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
