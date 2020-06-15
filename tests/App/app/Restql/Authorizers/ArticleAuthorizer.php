<?php

namespace Testing\App\Restql\Authorizers;

use Restql\Authorizer;

final class ArticleAuthorizer extends Authorizer
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
}
