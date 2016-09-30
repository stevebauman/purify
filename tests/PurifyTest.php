<?php

namespace Stevebauman\Purify\Tests;

use Stevebauman\Purify\Facades\Purify;

class PurifyTest extends FunctionalTestCase
{
    public $testInput = '<script>alert("Harmful Script");</script> <p style="a {color: blue;}" class="a-different-class">Test</p>';

    public function testClean()
    {
        $cleaned = Purify::clean($this->testInput);

        $expected = '<p class="a-different-class">Test</p>';

        $this->assertEquals($expected, $cleaned);
    }

    public function testCleanArray()
    {
        $cleaned = Purify::clean([$this->testInput, $this->testInput]);

        $expected = ['<p class="a-different-class">Test</p>', '<p class="a-different-class">Test</p>'];

        $this->assertEquals($expected, $cleaned);
    }

    public function testCleanMergeConfig()
    {
        $input = '<a href="http://www.google.ca">Google</a>';

        $cleaned = Purify::clean($input);

        $this->assertEquals($cleaned, $input);

        $settings = [
            'HTML.TargetBlank' => true,
        ];

        $cleanedTargetBlank = Purify::clean($input, $settings);

        $expected = '<a href="http://www.google.ca" target="_blank" rel="noreferrer">Google</a>';

        $this->assertEquals($expected, $cleanedTargetBlank);
    }

    public function testCleanDoNotMergeConfig()
    {
        $settings = [
            'HTML.ForbiddenElements' => 'p',
        ];

        $cleaned = Purify::clean($this->testInput, $settings, false);

        $this->assertEquals('Test', $cleaned);
    }

    public function testGetPurifier()
    {
        $this->assertInstanceOf('HTMLPurifier', Purify::getPurifier());
    }

    public function testGetPurifierConfiguration()
    {
        $this->assertInstanceOf('HTMLPurifier_Config', Purify::getPurifierConfig());
    }

    public function testSetPurifier()
    {
        $purifier = new \HTMLPurifier();

        $this->assertInstanceOf('HTMLPurifier', Purify::setPurifier($purifier)->getPurifier());
    }

    public function testSetPurifierConfig()
    {
        $config = \HTMLPurifier_Config::createDefault();

        $this->assertInstanceOf('HTMLPurifier_Config', Purify::setPurifierConfig($config)->getPurifierConfig());
    }
}
