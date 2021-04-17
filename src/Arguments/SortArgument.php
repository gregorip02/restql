<?php

namespace Restql\Arguments;

use Restql\ModelArgument;

class SortArgument extends ModelArgument
{
    /**
     * Determines if the argument accepts implicit values.
     *
     * @var boolean
     */
    protected $hasImplicitValues = true;

    /**
     * Get default values.
     *
     * @return array
     */
    public function getDefaultArgumentValues(): array
    {
        return [
            'column' => $this->getKeyName(),
            'direction' => 'asc'
        ];
    }
}
