<?php

namespace App\Restql\Authorizers;

use Restql\Authorizer;

final class CommentAuthorizer extends Authorizer
{
    /**
     * Can get one or more comment resources.
     *
     * @return bool
     */
    public static function get(): bool
    {
        return true;
    }
}
