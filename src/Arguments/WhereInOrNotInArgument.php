<?php

namespace Restql\Arguments;

use Restql\Arguments\WhereArgument;

class WhereInOrNotInArgument extends WhereArgument
{
    /**
     * Get default argument values.
     *
     * @return array
     */
    public function getDefaultArgumentValues(): array
    {
        return [
            'column' => $this->getKeyName(),
            'value'  => []
        ];
    }
}
