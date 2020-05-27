<?php

namespace Restql\Exceptions;

use Exception;

final class InvalidSchemaDefinitionException extends Exception
{
    public function __construct(string $keyName, string $message = '')
    {
        $slug = 'Invalid schema definition for %s key. %s';

        parent::__construct(sprintf($slug, $keyName, $message), 1);
    }
}
