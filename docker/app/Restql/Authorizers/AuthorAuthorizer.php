<?php

namespace App\Restql\Authorizers;

use Restql\Authorizer;

final class AuthorAuthorizer extends Authorizer
{
    /**
     * Can get one or more author resources.
     *
     * @return bool
     */
    public static function get(): bool
    {
        return true;
    }

    /**
     * Can create one or more author resources.
     *
     * @return bool
     */
    public static function post(): bool
    {
        return true;
    }
}
