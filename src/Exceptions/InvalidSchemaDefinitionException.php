<?php

namespace Restql\Exceptions;

use Exception;

final class InvalidSchemaDefinitionException extends Exception
{
    public function __construct(string $keyName, string $message = '')
    {
        $message = sprintf('Invalid schema definition for %s key. %s', $keyName, $message);

        parent::__construct($message, 1);
    }
}
