<?php

namespace Restql\Authorizers;

use Restql\Authorizer;

final class PermissiveAuthorizer extends Authorizer
{
    /**
     * Can get one or more resources.
     *
     * @param  array $clausules
     * @return bool
     */
    public static function get($clausules = []): bool
    {
        return true;
    }
}
