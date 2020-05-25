<?php

namespace Restql\Arguments;

use Restql\Contracts\ArgumentContract;

class SelectArgument extends ModelArgument implements ArgumentContract
{
    /**
     * Get the argument values as array.
     *
     * @return array
     */
    public function values(): array
    {
        $values = $this->excludeHiddenAttributes();

        $pkName = $this->getKeyName();

        if (! in_array($pkName, $values)) {
            $values[] = $pkName;
        }

        return $values;
    }
}
