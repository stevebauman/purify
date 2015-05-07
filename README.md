![Purify Banner]
(https://github.com/stevebauman/purify/blob/master/purify-banner.jpg)

### Description

Purify is an HTML Purifier helper for Laravel 4 / 5. It utilizes the fantastic package [HTMLPurifier](https://github.com/ezyang/htmlpurifier)
by [ezyang](https://github.com/ezyang).

### Installation

To install Purify, insert the following require in your `composer.json` file:

    "stevebauman/purify": "1.0.*"

Now run a `composer update` on your project source.

Once that's finished, insert the service provider in your `app/config/app.php`
(or `config/app.php` for Laravel 5)configuration file:

    'Stevebauman\Purify\PurifyServiceProvider'
    
You can also use the facade if you wish:

    'Purify' => 'Stevebauman\Purify\Facades\Purify'

### Usage

To clean a users input, simply use the clean method:

    $input = '<script>alert("Harmful Script");</script> <p style="a style" class="a-different-class">Test</p>';
    
    $cleaned = Purify::clean($input);
    
    echo $cleaned; // Returns '<p class="a-different-class">Test</p>'

Need to purify an array of user input? Just pass in an array:

    $inputArray = [
        '<script>alert("Harmful Script");</script> <p style="a style" class="a-different-class">Test</p>',
        '<script>alert("Harmful Script");</script> <p style="a style" class="a-different-class">Test</p>',
    ];
    
    $cleaned = Purfiy::clean($inputArray);
    
    var_dump($cleaned); // Returns [0] => '<p class="a-different-class">Test</p>' [1] => '<p class="a-different-class">Test</p>'

### Configuration

