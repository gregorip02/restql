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

    /**
     * Can create one or more resources.
     *
     * @param  array $clausules
     * @return bool
     */
    public static function post(array $clausules = []): bool
    {
        return false;
    }

    /**
     * Can update one or more resources.
     *
     * @param  array $clausules
     * @return bool
     */
    public static function put(array $clausules = []): bool
    {
        return false;
    }

    /**
     * Can update one or more resources.
     *
     * @param  array $clausules
     * @return bool
     */
    public static function patch(array $clausules = []): bool
    {
        return self::put($clausules);
    }

    /**
     * Can delete one or more resources.
     *
     * @param  array $clausules
     * @return bool
     */
    public static function delete(array $clausules = []): bool
    {
        return false;
    }
}
