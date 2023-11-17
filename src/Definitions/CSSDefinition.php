<?php

namespace Stevebauman\Purify\Definitions;

use HTMLPurifier_CSSDefinition;

interface CSSDefinition
{
    /**
     * Apply rules to the CSS Purifier definition.
     *
     * @param HTMLPurifier_CSSDefinition $definition
     *
     * @return void
     */
    public static function apply(HTMLPurifier_CSSDefinition $definition);
}
