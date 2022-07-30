<?php

namespace Stevebauman\Purify\Tests;

use Stevebauman\Purify\Facades\Purify;
use Stevebauman\Purify\PurifyManager;
use Stevebauman\Purify\PurifyServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

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
