<?php

namespace Restql\Authorizers;

use Illuminate\Http\Request;
use Restql\Authorizer;

final class WhoamiAuthorizer extends Authorizer
{
    public static function get(Request $request): bool
    {
        return true;
    }
}
