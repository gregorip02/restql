<?php

namespace Restql\Contracts;

use Illuminate\Http\Request;

interface AuthorizerContract
{
        /**
     * Can get one or more resources.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    public static function get(Request $request): bool;

    /**
     * Can create one or more resources.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    public static function post(Request $request): bool;

    /**
     * Can update one or more resources.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    public static function put(Request $request): bool;

    /**
     * Can update one or more resources.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    public static function patch(Request $request): bool;

    /**
     * Can delete one or more resources.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    public static function delete(Request $request): bool;
}
