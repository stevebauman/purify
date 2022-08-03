<?php

namespace Stevebauman\Purify\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Stevebauman\Purify\Facades\Purify;

class PurifyHtmlOnSet implements CastsAttributes
{
    /**
     * The name of the config to use for purification.
     *
     * @var string|null
     */
    protected $config;

    /**
     * Constructor.
     *
     * @param string|null $config
     */
    public function __construct($config = null)
    {
        $this->config = $config;
    }

    /**
     * Cast the given value.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string                              $key
     * @param mixed                               $value
     * @param array                               $attributes
     *
     * @return string|array
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return $value;
    }

    /**
     * Purify the value for storage.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string                              $key
     * @param mixed                               $value
     * @param array                               $attributes
     *
     * @return array|string
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return Purify::config($this->config)->clean($value);
    }
}
