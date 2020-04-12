<?php

namespace Restql\Exceptions;

use Exception;

final class InvalidClausuleArgument extends Exception
{
    public function __construct(string $expected, string $received)
    {
        $message = sprintf('Expected %s, received %s', $expected, $received);

        parent::__construct($message, 1);
    }
}
