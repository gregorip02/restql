<?php

namespace Restql\Arguments;

use Restql\Argument;

class SortArgument extends Argument
{
    /**
     * The argument default keys.
     *
     * @var array
     */
    public $keys = ['column', 'direction'];

    /**
     * The argument default values.
     *
     * @var array
     */
    public $defaults = ['id', 'asc'];
}
