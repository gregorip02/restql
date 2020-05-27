<?php

namespace Restql\Contracts;

interface AuthorizerContract
{
    /**
     * Can get one or more resources.
     *
     * @param  array $clausules
     * @return bool
     */
    public static function get(array $clausules = []): bool;

    /**
     * Can create one or more resources.
     *
     * @param  array $clausules
     * @return bool
     */
    public static function post(array $clausules = []): bool;

    /**
     * Can update one or more resources.
     *
     * @param  array $clausules
     * @return bool
     */
    public static function put(array $clausules = []): bool;

    /**
     * Can update one or more resources.
     *
     * @param  array $clausules
     * @return bool
     */
    public static function patch(array $clausules = []): bool;

    /**
     * Can delete one or more resources.
     *
     * @param  array $clausules
     * @return bool
     */
    public static function delete(array $clausules = []): bool;
}
