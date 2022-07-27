# Purify

[![GitHub Actions](https://img.shields.io/github/workflow/status/stevebauman/purify/run-tests.svg?style=flat-square)](https://github.com/stevebauman/purify/actions)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/stevebauman/purify.svg?style=flat-square)](https://scrutinizer-ci.com/g/stevebauman/purify/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/stevebauman/purify.svg?style=flat-square)](https://packagist.org/packages/stevebauman/purify)
[![Total Downloads](https://img.shields.io/packagist/dt/stevebauman/purify.svg?style=flat-square)](https://packagist.org/packages/stevebauman/purify)
[![License](https://img.shields.io/packagist/l/stevebauman/purify.svg?style=flat-square)](https://packagist.org/packages/stevebauman/purify)

Purify is a Laravel wrapper around [HTMLPurifier](https://github.com/ezyang/htmlpurifier) by [ezyang](https://github.com/ezyang).

### Requirements

-   PHP >= 7.1
-   Laravel >= 5.5

### Installation

To install Purify, run the following in the root of your project:

```bash
composer require stevebauman/purify
```

Then, publish the configuration file using:

```bash
php artisan vendor:publish --provider="Stevebauman\Purify\PurifyServiceProvider"
```

If you are using Lumen, you should copy the config file `purify.php` by hand, and add this line to your `bootstrap/app.php`:

```php
$app->register(Stevebauman\Purify\PurifyServiceProvider::class);
```

### Usage

##### Cleaning a String

To clean a users input, simply use the clean method:

```php
$input = '<script>alert("Harmful Script");</script> <p style="border:1px solid black" class="text-gray-700">Test</p>';

// Returns '<p>Test</p>'
$cleaned = Purify::clean($input);
```

##### Cleaning an Array

Need to purify an array of user input? Just pass in an array:

```php
$array = [
    '<script>alert("Harmful Script");</script> <p style="border:1px solid black" class="text-gray-700">Test</p>',
    '<script>alert("Harmful Script");</script> <p style="border:1px solid black" class="text-gray-700">Test</p>',
];

$cleaned = Purify::clean($array);

// array [
//  '<p>Test</p>',
//  '<p>Test</p>',
// ]
var_dump($cleaned);
```

##### Dynamic Configuration

Need a different configuration for a single input? Pass in a configuration array into the second parameter:

> **Note**: Configuration passed into the second parameter
> is **not** merged with your default configuration.

```php
$config = ['HTML.Allowed' => 'div,b,a[href]'];

$cleaned = Purify::config($config)->clean($input);
```

### Practices

If you're looking into sanitization, you're likely wanting to sanitize inputted user HTML content
that is then stored in your database to be rendered onto your application.

In this scenario, it's likely best practice to sanitize on the _way out_ instead of the on the _way in_.
Remember, the **database doesn't care what text it contains**.

This way you can allow anything to be inserted in the database, and have strong sanization rules on the way out.

This helps tremendously if you change your sanization requirements later down the line,
then all rendered content will follow these sanization rules.

### Configuration

Inside the configuration file, the entire settings array is passed directly
to the HTML Purifier configuration, so feel free to customize it however
you wish. You can specify multiple configuration sets as you desire.
Simply call `Purify::config($name)->clean($input)` to use another set of configuration.

For the configuration documentation, please visit the
HTML Purifier Website:

http://htmlpurifier.org/live/configdoc/plain.html