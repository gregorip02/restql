<?php

namespace Restql\Arguments;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Restql\Contracts\ArgumentContract;

class WhereArgument extends ModelArgument implements ArgumentContract
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
    public function getValues(): array
    {
        if ($this->isImplicitValue()) {
            /// When an implicit type value is received, it will be assumed that
            /// it corresponds to the value of the primary column of the model.
            return [
                "value" => $this->values->first()
            ];
        }

        return $this->values->toArray();
    }

    /**
     * Get default values.
     *
     * @return array
     */
    public function getDefaultArgumentValues(): array
    {
        return [
            'column'   => $this->getKeyName(),
            'operator' => '=',
            'value'    => null
        ];
    }
}
