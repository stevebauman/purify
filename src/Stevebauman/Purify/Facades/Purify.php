<?php

namespace Stevebauman\Purify\Facades;

use Illuminate\Support\Facades\Facade;

class Purify extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'purify';
    }
}
