<?php

namespace App\Restql\Authorizers;

use Restql\Authorizer;
use Illuminate\Http\Request;

final class CommentAuthorizer extends Authorizer
{
    /**
     * Can get one or more comment resources.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    public static function get(Request $request): bool
    {
        return true;
    }
}
