<?php

namespace Stevebauman\Purify\Tests;

class PurifyTest extends FunctionalTestCase
{
    public function testClean()
    {
        $input = '<script>alert("Harmful Script");</script> <p style="a style" class="a-different-class">Test</p>';

        $cleaned = $this->purifier->clean($input);

        $expected = '<p class="a-different-class">Test</p>';

        $this->assertEquals($expected, $cleaned);
    }
}