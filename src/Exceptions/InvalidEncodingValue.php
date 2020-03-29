<?php

namespace Restql\Exceptions;

use Exception;

final class InvalidEncodingValue extends Exception
{
    public function __construct(string $key)
    {
        $message = sprintf('Your query param not have a valid base64 value.', $key);

        parent::__construct($message, 1);
    }
}
