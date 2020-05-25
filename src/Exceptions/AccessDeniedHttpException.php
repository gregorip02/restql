<?php

namespace Restql\Exceptions;

use Restql\SchemaDefinition;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException as E;

final class AccessDeniedHttpException extends E
{
    public function __construct(SchemaDefinition $schema, string $method)
    {
        $message = 'You can\'t access to %s via %s method.';

        $message = sprintf($message, $schema->getKeyName(), $method);

        parent::__construct($message);
    }
}
