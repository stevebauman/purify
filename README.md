# Purify

[![Travis CI](https://img.shields.io/travis/stevebauman/purify.svg?style=flat-square)](https://travis-ci.org/stevebauman/purify)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/stevebauman/purify.svg?style=flat-square)](https://scrutinizer-ci.com/g/stevebauman/purify/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/stevebauman/purify.svg?style=flat-square)](https://packagist.org/packages/stevebauman/purify)
[![Total Downloads](https://img.shields.io/packagist/dt/stevebauman/purify.svg?style=flat-square)](https://packagist.org/packages/stevebauman/purify)
[![License](https://img.shields.io/packagist/l/stevebauman/purify.svg?style=flat-square)](https://packagist.org/packages/stevebauman/purify)

### Description

Purify is an HTML Purifier helper for Laravel 5. It utilizes the fantastic package [HTMLPurifier](https://github.com/ezyang/htmlpurifier)
by [ezyang](https://github.com/ezyang). All credit for purification goes to him.

### Installation

To install Purify, insert the following require in your `composer.json` file:

    "stevebauman/purify": "1.1.*"

Now run a `composer update` on your project source.

Once that's finished, insert the service provider in your `app/config/app.php`
(or `config/app.php` for Laravel 5) configuration file:

    'Stevebauman\Purify\PurifyServiceProvider'
    
You can also use the facade if you wish:

    'Purify' => 'Stevebauman\Purify\Facades\Purify'

### Usage

##### Cleaning a String

To clean a users input, simply use the clean method:

```php
$input = '<script>alert("Harmful Script");</script> <p style="a style" class="a-different-class">Test</p>';

$cleaned = Purify::clean($input);

echo $cleaned; // Returns '<p class="a-different-class">Test</p>'
```

##### Cleaning an Array

Need to purify an array of user input? Just pass in an array:

```php
$inputArray = [
    '<script>alert("Harmful Script");</script> <p style="a style" class="a-different-class">Test</p>',
    '<script>alert("Harmful Script");</script> <p style="a style" class="a-different-class">Test</p>',
];

$cleaned = Purfiy::clean($inputArray);

var_dump($cleaned); // Returns [0] => '<p class="a-different-class">Test</p>' [1] => '<p class="a-different-class">Test</p>'
```

##### Dynamic Configuration

Need to add or modify rules for a single input? Pass in a configuration array into the second parameter:

```php
$configuration = ['HTML.Allowed' => 'div,b,a[href]'];

$cleaned = Purify::clean($input, $configuration);
```

> **Note**: Configuration passed into the second parameter is merged with the current configuration and will overwrite array keys you supply.
This allows you to add settings on the fly. Simply pass `false` into the third parameter if you **do not** want the configuration merged.

```php
$configuration = ['HTML.Allowed' => 'div,b,a[href]'];

$cleaned = Purify::clean($input, $configuration, $merge = false);
```

##### Replacing the HTML Purifier instance

Need to replace the HTML Purifier instance with your own? Call the `setPurifier()` method:

```php
$purifier = new HTMLPurifier();

Purify::setPurifier($purifier);
```

##### Replacing the HTML Purifier Configuration instance

Need to replace the HTML Purifier Configuration instance with your own? Call the `setPurifierConfig()` method:

```php
$settings = ['HTML.Allowed' => 'div,b,a[href]'];

$configuration = new HTMLPurifier_Config($settings);

Purify::setPurifierConfig($configuration);
```

### Configuration

Inside the configuration file, the entire settings array is passed directly to the HTML Purifier configuration, so feel
free to customize it however you wish. For the configuration documentation, please visit the HTML Purifier Website:

http://htmlpurifier.org/live/configdoc/plain.html
