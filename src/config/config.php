<?php

/**
 * The Purify Configuration File.
 *
 * The configuration settings array is passed directly to HTMLPurifier.
 * Feel free to add / remove / customize these attributes as you wish.
 */
return [

    'settings' => [

        'Core.Encoding'            => 'UTF-8',
        'Cache.SerializerPath'     => storage_path('purify'),
        'HTML.Doctype'             => 'XHTML 1.0 Strict',
        'HTML.Allowed'             => 'div,b,strong,i,em,a[href|title],ul,ol,li,p[style],br,span[style],img[width|height|alt|src]',
        'CSS.AllowedProperties'    => 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align',
        'AutoFormat.AutoParagraph' => true,
        'AutoFormat.RemoveEmpty'   => true,

    ],

];
