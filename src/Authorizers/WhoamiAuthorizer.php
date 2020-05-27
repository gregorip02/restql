<?php

namespace Restql\Authorizers;

use Illuminate\Http\Request;
use Restql\Authorizer;

final class WhoamiAuthorizer extends Authorizer
{
    /**
     * Can access via get method.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    public static function get(Request $request): bool
    {
        return true;
    }
}
