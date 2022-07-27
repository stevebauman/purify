<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Config
    |--------------------------------------------------------------------------
    |
    | This option defines the default config that are provided to HTMLPurifier.
    |
    */

    'default' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Config sets
    |--------------------------------------------------------------------------
    |
    | Here you may configure various sets of configuration for differentiated use of HTMLPurifier.
    | A specific set of configuration can be applied by calling the "config($name)" method on
    | a Purify instance. Feel free to add/remove/customize these attributes as you wish.
    |
    | Documentation: http://htmlpurifier.org/live/configdoc/plain.html
    |
    |   Core.Encoding               The encoding to convert input to.
    |   HTML.Doctype                Doctype to use during filtering.
    |   HTML.Allowed                The allowed HTML Elements with their allowed attributes.
    |   HTML.ForbiddenElements      The forbidden HTML elements. Elements that are listed in this
    |                               string will be removed, however their content will remain.
    |   CSS.AllowedProperties       The Allowed CSS properties.
    |   AutoFormat.AutoParagraph    Newlines are converted in to paragraphs whenever possible.
    |   AutoFormat.RemoveEmpty      Remove empty elements that contribute no semantic information to the document.
    |
    */

    'configs' => [
        'default' => [
            'Core.Encoding' => 'utf-8',
            'Cache.SerializerPath' => storage_path('app/purify'),
            'HTML.Doctype' => 'XHTML 1.0 Strict',
            'HTML.Allowed' => 'h1,h2,h3,h4,h5,h6,b,strong,i,em,a[href|title],ul,ol,li,p[style],br,span,img[width|height|alt|src]',
            'HTML.ForbiddenElements' => '',
            'CSS.AllowedProperties' => 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align',
            'AutoFormat.AutoParagraph' => false,
            'AutoFormat.RemoveEmpty' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | HTMLPurifier definitions
    |--------------------------------------------------------------------------
    |
    | Here you may alter the HTML definitions used by HTMLPurifier. You can
    | add elements and attributes as needed. We've already added a few
    | common examples.
    |
    | Note that these definitions are applied to every Purifier instance.
    |
    | Documentation: http://htmlpurifier.org/docs/enduser-customize.html
    |
    */

    'definitions' => function(HTMLPurifier_HTMLDefinition $definition) {
        $definition->addElement('u', 'Inline', 'Inline', 'Common');
        $definition->addAttribute('a', 'target', 'Enum#_blank,_self,_target,_top');
    },

    /*
    |--------------------------------------------------------------------------
    | Serializer location
    |--------------------------------------------------------------------------
    |
    | The location where HTMLPurifier can store its temporary serializer files.
    | The filepath should be accessible and writable by the web server.
    | A good place for this is in the framework's own storage path.
    |
    */

    'serializer' => storage_path('app/purify'),

];
