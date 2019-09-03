# Purify

[![Travis CI](https://img.shields.io/travis/stevebauman/purify.svg?style=flat-square)](https://travis-ci.org/stevebauman/purify)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/stevebauman/purify.svg?style=flat-square)](https://scrutinizer-ci.com/g/stevebauman/purify/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/stevebauman/purify.svg?style=flat-square)](https://packagist.org/packages/stevebauman/purify)
[![Total Downloads](https://img.shields.io/packagist/dt/stevebauman/purify.svg?style=flat-square)](https://packagist.org/packages/stevebauman/purify)
[![License](https://img.shields.io/packagist/l/stevebauman/purify.svg?style=flat-square)](https://packagist.org/packages/stevebauman/purify)

Purify is an HTML input sanitizer for Laravel.

It utilizes [HTMLPurifier](https://github.com/ezyang/htmlpurifier)
by [ezyang](https://github.com/ezyang).

### Requirements

- PHP >= 7.1
- Laravel >= 5.5

### Installation

To install Purify, run the following in the root of your project:

```bash
composer require stevebauman/purify
```

Then, publish the configuration file using:

```bash
php artisan vendor:publish --provider="Stevebauman\Purify\PurifyServiceProvider"
```

If you are using Lumen, you should copy the config file `purify.php` by hand, and add this line to your bootstrap/app.php:

```php
$app->register(Stevebauman\Purify\PurifyServiceProvider::class);
```

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
$array = [
    '<script>alert("Harmful Script");</script> <p style="a style" class="a-different-class">Test</p>',
    '<script>alert("Harmful Script");</script> <p style="a style" class="a-different-class">Test</p>',
];

$cleaned = Purify::clean($array);

var_dump($cleaned); // Returns [0] => '<p class="a-different-class">Test</p>' [1] => '<p class="a-different-class">Test</p>'
```

##### Dynamic Configuration

Need a different configuration for a single input? Pass in a configuration array into the second parameter:

```php
$config = ['HTML.Allowed' => 'div,b,a[href]'];

$cleaned = Purify::clean($input, $config);
```

> **Note**: Configuration passed into the second parameter is
> **not** merged with your current configuration.

```php
$config = ['HTML.Allowed' => 'div,b,a[href]'];

$cleaned = Purify::clean($input, $config);
```

##### Replacing the HTML Purifier instance

Need to replace the HTML Purifier instance with your own? Call the `setPurifier()` method:

```php
$purifier = new HTMLPurifier();

Purify::setPurifier($purifier);
```

### Practices

If you're looking into sanitization, you're likely wanting to sanitize inputted user HTML content
that is then stored in your database to be rendered onto your application.

In this scenario, it's likely best practice to sanitize on the *way out* instead of the on the *way in*.
Remember, the **database doesn't care what text it contains**.

This way you can allow anything to be inserted in the database, and have strong sanization rules on the way out.

This helps tremendously if you change your sanization requirements later down the line,
then all rendered content will follow these sanization rules.

### Configuration

Inside the configuration file, the entire settings array is passed directly
to the HTML Purifier configuration, so feel free to customize it however
you wish. For the configuration documentation, please visit the
HTML Purifier Website:

http://htmlpurifier.org/live/configdoc/plain.html

#### Custom Configuration Rules

There's mutliple ways of creating custom rules on the HTML Purifier instance.

Below is an example service provider you can use as a starting point to add rules to the instance. This provider gives compatibility with Basecamp's Trix WYSIWYG editor:

Credit to [Antonio Primera](https://github.com/AntonioPrimera) for resolving some [HTML Purifier configuration issues](https://github.com/stevebauman/purify/issues/7) with trix.

```php
<?php

namespace App\Providers;

use HTMLPurifier_HTMLDefinition;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Support\ServiceProvider;

class PurifySetupProvider extends ServiceProvider
{
    const DEFINITION_ID = 'trix-editor';
    const DEFINITION_REV = 1;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /** @var \HTMLPurifier $purifier */
        $purifier = Purify::getPurifier();

        /** @var \HTMLPurifier_Config $config */
        $config = $purifier->config;

        $config->set('HTML.DefinitionID', static::DEFINITION_ID);
        $config->set('HTML.DefinitionRev', static::DEFINITION_REV);

        if ($def = $config->maybeGetRawHTMLDefinition()) {
            $this->setupDefinitions($def);
        }

        $purifier->config = $config;
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Adds elements and attributes to the HTML purifier
     * definition required by the trix editor.
     *
     * @param HTMLPurifier_HTMLDefinition $def
     */
    protected function setupDefinitions(HTMLPurifier_HTMLDefinition $def)
    {
        $def->addElement('figure', 'Inline', 'Inline', 'Common');
        $def->addAttribute('figure', 'class', 'Text');

        $def->addElement('figcaption', 'Inline', 'Inline', 'Common');
        $def->addAttribute('figcaption', 'class', 'Text');
        $def->addAttribute('figcaption', 'data-trix-placeholder', 'Text');

        $def->addAttribute('a', 'rel', 'Text');
        $def->addAttribute('a', 'tabindex', 'Text');
        $def->addAttribute('a', 'contenteditable', 'Enum#true,false');
        $def->addAttribute('a', 'data-trix-attachment', 'Text');
        $def->addAttribute('a', 'data-trix-content-type', 'Text');
        $def->addAttribute('a', 'data-trix-id', 'Number');

        $def->addElement('span', 'Block', 'Flow', 'Common');
        $def->addAttribute('span', 'data-trix-cursor-target', 'Enum#right,left');
        $def->addAttribute('span', 'data-trix-serialize', 'Enum#true,false');

        $def->addAttribute('img', 'data-trix-mutable', 'Enum#true,false');
        $def->addAttribute('img', 'data-trix-store-key', 'Text');
    }
}
```

After this service provider is created, make sure you insert it into your `providers` array in the `app/config.php`
file, and update your `HTML.Allowed` string in the `config/purify.php` file.

> **Note**: Remember that after this definition is created, and you have ran `Purify::clean()`, the definition will be cached, and you will have to clear it from your `storage/app/purify` folder if you want to make changes to the definition.
>
> Otherwise, you will have to change the definition version number or ID for it to be re-cached.
