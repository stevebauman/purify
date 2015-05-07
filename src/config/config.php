<?php

/**
 * The Purify Configuration File.
 *
 * The default configuration settings array is passed directly to HTMLPurifier.
 * Feel free to add / remove / customize these attributes as you wish.
 */
return [

    'settings' => [

        'default' => [
            'Core.Encoding'            => 'UTF-8',
            'Cache.SerializerPath'     => storage_path('purify'),
            'HTML.Doctype'             => 'XHTML 1.0 Strict',
            'HTML.Allowed'             => 'div,b,strong,i,em,a[href|title],ul,ol,li,p[style],br,span[style],img[width|height|alt|src]',
            'CSS.AllowedProperties'    => 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align',
            'AutoFormat.AutoParagraph' => true,
            'AutoFormat.RemoveEmpty'   => true,
        ],

        "youtube" => [
            "HTML.SafeIframe" => 'true',
            "URI.SafeIframeRegexp" => "%^(http://|https://|//)(www.youtube.com/embed/|player.vimeo.com/video/)%",
        ],

    ],

];
