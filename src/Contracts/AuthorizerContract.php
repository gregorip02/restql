<?php

namespace Restql\Contracts;

interface AuthorizerContract
{
    /**
     * Can get one or more resources.
     *
     * @param  array $clausules
     * @return bool
     */
    public static function get(array $clausules = []): bool;
}
