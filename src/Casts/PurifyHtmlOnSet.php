<?php

namespace Stevebauman\Purify\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Stevebauman\Purify\Facades\Purify;

class PurifyHtmlOnSet implements CastsAttributes
{
    protected $config = null;

    public function __construct($config = null)
    {
        $this->config = $config;
    }

    public function get($model, string $key, $value, array $attributes)
    {
        return $value;
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return Purify::config($this->config)->clean($value);
    }
}
