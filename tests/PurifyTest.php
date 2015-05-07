<?php

namespace Stevebauman\Purify\Tests;

class PurifyTest extends FunctionalTestCase
{
    public $testInput = '<script>alert("Harmful Script");</script> <p style="a style" class="a-different-class">Test</p>';

    public function testClean()
    {
        $cleaned = $this->purifier->clean($this->testInput);

        $expected = '<p class="a-different-class">Test</p>';

        $this->assertEquals($expected, $cleaned);
    }

    public function testCleanArray()
    {
        $cleaned = $this->purifier->clean([$this->testInput, $this->testInput]);

        $expected = ['<p class="a-different-class">Test</p>', '<p class="a-different-class">Test</p>'];

        $this->assertEquals($expected, $cleaned);
    }

    public function testCleanMergeConfig()
    {

    }
}