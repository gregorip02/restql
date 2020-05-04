<?php

namespace Restql\Contracts;

interface ArgumentContract
{
    /**
     * Get the default argument values.
     *
     * @return array
     */
    public function getDefaultArgumentValues(): array;
}
