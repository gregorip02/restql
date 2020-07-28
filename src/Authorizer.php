<?php

namespace Restql;

use Restql\Contracts\AuthorizerContract;

class Authorizer implements AuthorizerContract
{
    /**
     * Can get one or more resources.
     *
     * @param  array $clausules
     * @return bool
     */
    public static function get(array $clausules = []): bool
    {
        return false;
    }
}
