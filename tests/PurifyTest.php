<?php

namespace Stevebauman\Purify\Tests;

use HTMLPurifier;
use Illuminate\Support\Facades\File;
use Stevebauman\Purify\Facades\Purify;
use Stevebauman\Purify\PurifyServiceProvider;

class PurifyTest extends TestCase
{
    public $testInput = '<script>alert("Harmful Script");</script><p style="a {color: #0000ff;}" class="a-different-class">Test</p>';

    public function test_configuration_file_is_published_and_storage_directory_exists()
    {
        $this->artisan('vendor:publish', ['--provider' => PurifyServiceProvider::class]);

        $this->assertFileExists(config_path('purify.php'));
        $this->assertDirectoryExists(storage_path('app/purify'));

        File::delete(config_path('purify.php'));
        File::deleteDirectory(storage_path('app/purify'));
    }

    public function test_input_is_sanitized()
    {
        $cleaned = Purify::clean($this->testInput);

        $expected = '<p class="a-different-class">Test</p>';

        $this->assertEquals($expected, $cleaned);
    }

    public function test_input_arrays_are_sanitized()
    {
        $cleaned = Purify::clean([$this->testInput, $this->testInput]);

        $expected = ['<p class="a-different-class">Test</p>', '<p class="a-different-class">Test</p>'];

        $this->assertEquals($expected, $cleaned);
    }

    public function test_given_config_overwrites_default_config()
    {
        $input = '<a href="http://www.google.ca">Google</a>';

        $cleaned = Purify::clean($input);

        $this->assertEquals($cleaned, $input);

        $settings = ['HTML.TargetBlank' => true];

        $cleanedTargetBlank = Purify::clean($input, $settings);

        $expected = '<a href="http://www.google.ca" target="_blank" rel="noreferrer noopener">Google</a>';

        $this->assertEquals($expected, $cleanedTargetBlank);
    }

    public function test_purifier_instance_is_accessible()
    {
        $this->assertInstanceOf(HTMLPurifier::class, Purify::getPurifier());
    }

    public function test_purifier_instance_can_be_set()
    {
        $purifier = new HTMLPurifier();

        $this->assertInstanceOf(HTMLPurifier::class, Purify::setPurifier($purifier)->getPurifier());
    }
}
