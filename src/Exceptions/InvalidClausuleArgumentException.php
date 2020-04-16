<?php

namespace Restql\Exceptions;

use Exception;

final class InvalidClausuleArgumentException extends Exception
{
    public function __construct()
    {
        $message = 'The arguments passed to the clausule are incorrect, check your rules.';

        parent::__construct($message, 1);
    }
}
