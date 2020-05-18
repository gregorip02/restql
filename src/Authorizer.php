<?php

namespace Restql;

use Closure;
use Illuminate\Http\Request;
use Restql\Contracts\AuthorizerContract;

class Authorizer implements AuthorizerContract
{
    /**
     * Can get one or more resources.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    public static function get(Request $request): bool
    {
        return false;
    }

    /**
     * Can create one or more resources.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    public static function post(Request $request): bool
    {
        return false;
    }

    /**
     * Can update one or more resources.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    public static function put(Request $request): bool
    {
        return false;
    }

    /**
     * Can update one or more resources.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    public static function patch(Request $request): bool
    {
        return self::put($request);
    }

    /**
     * Can delete one or more resources.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    public static function delete(Request $request): bool
    {
        return false;
    }
}
