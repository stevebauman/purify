<?php

namespace Stevebauman\Purify\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Stevebauman\Purify\PurifyManager;
use Stevebauman\Purify\PurifyServiceProvider;

class TestCase extends BaseTestCase
{
    protected function getPackageAliases($app)
    {
        return ['Purify' => PurifyManager::class];
    }

    protected function getPackageProviders($app)
    {
        return [PurifyServiceProvider::class];
    }
}
