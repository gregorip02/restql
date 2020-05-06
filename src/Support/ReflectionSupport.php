<?php

namespace Restql\Support;

use ReflectionType;
use ReflectionClass;

final class ReflectionSupport extends ReflectionClass
{
    /**
     * Get the return type of an method.
     *
     * @param  string $method
     * @return string Returns a empty string if the return type aren't set in the class method.
     */
    public function getMethodReturnType(string $method): string
    {
        $type = $this->getMethod($method)->getReturnType();

        return $type instanceof ReflectionType ? $type->getName() : '';
    }

    /**
     * Check if the method return type is equal to $type passed as argument.
     *
     * @param  string $method
     * @param  string $type
     * @return bool
     */
    public function methodIs(string $method, string $type): bool
    {
        return $this->getMethodReturnType($method) === $type;
    }
}
