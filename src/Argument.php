<?php

namespace Restql;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Restql\Contracts\ArgumentContract;

class Argument implements ArgumentContract
{
    /**
     * The argument values
     *
     * @var \Illuminate\Support\Collection
     */
    protected $values;

    /**
     * Determines if the argument accepts implicit values.
     *
     * @var boolean
     */
    protected $hasImplicitValues = false;

    /**
     *
     * @param Collection $values [description]
     */
    public function __construct(Collection $values)
    {
        $this->values = $values;
    }

    /**
     * Get the default argument values.
     *
     * @return array
     */
    public function getDefaultArgumentValues(): array
    {
        return [];
    }

    /**
     * Get the argument values as array.
     *
     * @return array
     */
    public function getValues(): array
    {
        return $this->values->toArray();
    }

    /**
     * Determine if the incoming values are associative array.
     *
     * @return bool
     */
    public function isAssoc(): bool
    {
        return Arr::isAssoc($this->values->toArray());
    }

    /**
     * Determine if the incoming value is only one and also implicit.
     *
     * @return bool
     */
    public function isImplicitValue(): bool
    {
        return ! $this->isAssoc() && $this->countValues() === 1;
    }

    /**
     * Count the client incoming values.
     *
     * @return int
     */
    public function countValues(): int
    {
        return $this->values->count();
    }

    /**
     * Count the argument default values.
     *
     * @return int
     */
    public function countDefault(): int
    {
        return count($this->getDefaultArgumentValues());
    }

    /**
     * Merge the user argument values with defaults data.
     *
     * @return array
     */
    public function data(): array
    {
        if ($defaultValues = $this->getDefaultArgumentValues()) {
            $values = $this->getValues();

            /// Some arguments admit values of implicit type, that is, they
            /// can be interpreted by the clause in different ways.
            if (! Arr::isAssoc($values) && $this->hasImplicitValues) {
                /// Prevent exception with "Both parameters should have an equal
                /// number of elements" slicing the values.
                $slice = array_slice(array_keys($defaultValues), 0, count($values));

                return array_combine($slice, $values);
            }

            /// Combine the default values with the values sent by the client.
            /// The default values of these attributes are required to be associative.
            return array_merge($defaultValues, $values);
        }

        /// Returns the raw data.
        return $this->getValues();
    }
}
