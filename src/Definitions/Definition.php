<?php

namespace Stevebauman\Purify\Definitions;

use HTMLPurifier_HTMLDefinition;

interface Definition
{
    public static function apply(HTMLPurifier_HTMLDefinition $definition);
}
