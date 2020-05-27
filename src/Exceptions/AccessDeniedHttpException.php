<?php

namespace Restql\Exceptions;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException as E;

final class AccessDeniedHttpException extends E
{
    public function __construct(string $name, string $method)
    {
        $message = 'You can\'t access to %s via %s method.';

        $message = sprintf($message, $name, $method);

        parent::__construct($message);
    }
}
