<?php

namespace Restql\Authorizers;

use Restql\Authorizer;

final class WhoamiAuthorizer extends Authorizer
{
    /**
     * Can access via get method.
     *
     * @param array $clausules
     * @return bool
     */
    public static function get($clausules = []): bool
    {
        return true;
    }
}
