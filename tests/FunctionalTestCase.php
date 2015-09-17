<?php

namespace Stevebauman\Purify\Tests;

use Orchestra\Testbench\TestCase;

class FunctionalTestCase extends TestCase
{
    protected function getApplicationAliases($app)
    {
        return [
            'Purify' => 'Stevebauman\Purify\Facades\Purify',
        ];
    }

    protected function getApplicationProviders($app)
    {
        return [
            'Stevebauman\Purify\PurifyServiceProvider',
        ];
    }
}
