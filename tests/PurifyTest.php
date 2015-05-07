<?php

namespace Stevebauman\Purify\Tests;

class PurifyTest extends FunctionalTestCase
{
    public $testInput = '<script>alert("Harmful Script");</script> <p style="a {color: blue;}" class="a-different-class">Test</p>';

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
        $input = '<a href="http://www.google.ca">Google</a>';

        $cleaned = $this->purifier->clean($input);

        $this->assertEquals($cleaned, $input);

        $settings = [
            'HTML.TargetBlank' => true,
        ];

        $cleanedTargetBlank = $this->purifier->clean($input, $settings);

        $expected = '<a href="http://www.google.ca" target="_blank">Google</a>';

        $this->assertEquals($expected, $cleanedTargetBlank);
    }

    public function testCleanDoNotMergeConfig()
    {
        $settings = [
            'HTML.ForbiddenElements' => 'p',
        ];

        $cleaned = $this->purifier->clean($this->testInput, $settings, false);

        $this->assertEquals('Test', $cleaned);
    }
}
