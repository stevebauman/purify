<?php

namespace Stevebauman\Purify\Tests;

use Mockery;
use Stevebauman\Purify\Purify;

class FunctionalTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Purify
     */
    protected $purifier;
    
    public function setUp()
    {
        $configuration = Mockery::mock('Illuminate\Contracts\Config\Repository');

        $configuration->shouldReceive('get')->once()->andReturn([]);

        $this->purifier = new Purify($configuration);
    }
}
