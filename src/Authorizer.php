<?php

namespace Restql;

use Restql\Contracts\AuthorizerContract;

class Authorizer implements AuthorizerContract
{
    /**
     * Can get one or more resources.
     *
     * @return bool
     */
    public static function get(): bool
    {
        return false;
    }

    /**
     * Can create one or more resources.
     *
     * @return bool
     */
    public static function post(): bool
    {
        return false;
    }

    /**
     * Can update one or more resources.
     *
     * @return bool
     */
    public static function put(): bool
    {
        return false;
    }

    /**
     * Can update one or more resources.
     *
     * @return bool
     */
    public static function patch(): bool
    {
        return self::put();
    }

    /**
     * Can delete one or more resources.
     *
     * @return bool
     */
    public static function delete(): bool
    {
        return false;
    }
}
