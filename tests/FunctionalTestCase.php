<?php

namespace Stevebauman\Purify\Tests;

use Orchestra\Testbench\TestCase;

class FunctionalTestCase extends TestCase
{
    protected function getPackageAliases($app)
    {
        return [
            'Purify' => 'Stevebauman\Purify\Facades\Purify',
        ];
    }

    protected function getPackageProviders($app)
    {
        return [
            'Stevebauman\Purify\PurifyServiceProvider',
        ];
    }
}
