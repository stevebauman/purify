<?php

namespace Stevebauman\Purify\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
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
