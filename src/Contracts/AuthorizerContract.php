<?php

namespace Restql\Contracts;

interface AuthorizerContract
{
        /**
     * Can get one or more resources.
     *
     * @return bool
     */
    public static function get(): bool;

    /**
     * Can create one or more resources.
     *
     * @return bool
     */
    public static function post(): bool;

    /**
     * Can update one or more resources.
     *
     * @return bool
     */
    public static function put(): bool;

    /**
     * Can update one or more resources.
     *
     * @return bool
     */
    public static function patch(): bool;

    /**
     * Can delete one or more resources.
     *
     * @return bool
     */
    public static function delete(): bool;
}
