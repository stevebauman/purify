<?php

use Illuminate\Support\Facades\Facade;

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
