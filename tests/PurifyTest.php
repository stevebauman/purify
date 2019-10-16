<?php

namespace Stevebauman\Purify\Tests;

use HTMLPurifier;
use HTMLPurifier_ConfigSchema;
use Stevebauman\Purify\Facades\Purify;

class PurifyTest extends TestCase
{
    public $testInput = '<script>alert("Harmful Script");</script><p style="a {color: blue;}" class="a-different-class">Test</p>';

    /** @test */
    public function input_is_sanitized()
    {
        $cleaned = Purify::clean($this->testInput);

        $expected = '<p class="a-different-class">Test</p>';

        $this->assertEquals($expected, $cleaned);
    }

    /** @test */
    public function input_arrays_are_sanitized()
    {
        $cleaned = Purify::clean([$this->testInput, $this->testInput]);

        $expected = ['<p class="a-different-class">Test</p>', '<p class="a-different-class">Test</p>'];

        $this->assertEquals($expected, $cleaned);
    }

    /** @test */
    public function given_config_overwrites_default_config()
    {
        $input = '<a href="http://www.google.ca">Google</a>';

        $cleaned = Purify::clean($input);

        $this->assertEquals($cleaned, $input);

        $settings = [
            'HTML.TargetBlank' => true,
        ];

        $cleanedTargetBlank = Purify::clean($input, $settings);

        $expected = '<a href="http://www.google.ca" target="_blank" rel="noreferrer noopener">Google</a>';

        $this->assertEquals($expected, $cleanedTargetBlank);
    }

    /** @test */
    public function purify_loads_default_config()
    {
        $this->assertEquals(HTMLPurifier_ConfigSchema::instance()->defaults, Purify::getSettings());
    }

    /** @test */
    public function purifier_instance_is_accessible()
    {
        $this->assertInstanceOf(HTMLPurifier::class, Purify::getPurifier());
    }

    /** @test */
    public function purifier_instance_can_be_set()
    {
        $purifier = new HTMLPurifier();

        $this->assertInstanceOf(HTMLPurifier::class, Purify::setPurifier($purifier)->getPurifier());
    }
}
