<?php

namespace Restql\Exceptions;

use Exception;

final class ClausuleUnallowedMethodException extends Exception
{
    public function __construct(string $name, string $verb)
    {
        $message = 'You cant not access to <%s> via HTTP <%s> verb';

        $message = sprintf($message, ...func_get_args());

        parent::__construct($message, 1);
    }
}
