<?php

namespace Restql\Exceptions;

use Exception;

final class MissingArgumentException extends Exception
{
    public function __construct(string $name, string $clausule)
    {
        $message = 'You need pass %s because is required in %s clausule';

        $message = sprintf($message, ...func_get_args());

        parent::__construct($message, 1);
    }
}
