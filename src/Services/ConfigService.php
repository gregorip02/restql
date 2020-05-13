<?php

namespace Restql\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

class ConfigService
{
    /**
     * The package config collection.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $config;

    /**
     * Create class instance.
     */
    public function __construct()
    {
        $this->config = Collection::make(
            Config::get('restql', [])
        );
    }

    /**
     * Get an item from the collection by key.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->config->get($key, $default);
    }
}
