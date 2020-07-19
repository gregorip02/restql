<?php

namespace Restql\Arguments;

use Restql\ModelArgument;
use Restql\Contracts\ArgumentContract;

class WhereInOrNotInArgument extends ModelArgument implements ArgumentContract
{
    /**
     * Determines if the argument accepts implicit values.
     *
     * @var boolean
     */
    protected $hasImplicitValues = true;

    /**
     * Get the argument values as array.
     *
     * @return array
     */
    public function values(): array
    {
        if ($this->isImplicitValue()) {
            /// When an implicit type value is received, it will be assumed that
            /// it corresponds to the value of the primary column of the model.
            return [
                'value' => (array) $this->first()
            ];
        }

        return parent::values();
    }

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
