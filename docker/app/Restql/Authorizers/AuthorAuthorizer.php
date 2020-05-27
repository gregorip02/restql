<?php

namespace App\Restql\Authorizers;

use Restql\Authorizer;

final class AuthorAuthorizer extends Authorizer
{
    /**
     * Can get one or more author resources.
     *
     * @param  array $clausules
     * @return bool
     */
    public static function get(array $clausules = []): bool
    {
        return true;
    }

    /**
     * Can create one or more author resources.
     *
     * @param  array $clausules
     * @return bool
     */
    public static function post(array $clausules = []): bool
    {
        return true;
    }
}
