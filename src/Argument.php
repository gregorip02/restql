<?php

namespace Restql;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Restql\Contracts\ArgumentContract;

class Argument implements ArgumentContract
{
    /**
     * The argument values
     *
     * @var array
     */
    protected $values = [];

    /**
     * Determines if the argument accepts implicit values.
     *
     * @var boolean
     */
    protected $hasImplicitValues = false;

    /**
     * New Argument instace.
     *
     * @param array $values
     */
    public function __construct(array $values = [])
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
     * Get an attribute from the argument.
     *
     * @param  string $keyname
     * @param  mixed $default
     * @return mixed
     */
    public function getAttribute(string $keyname, $default = null)
    {
        return $this->values[$keyname] ?? $default;
    }

    /**
     * Get an attribute from the argument or throw an exception.
     *
     * @param  string $keyname
     * @return mixed|Exception
     */
    public function getAttributeOrFail(string $keyname)
    {
        return $this->getAttribute($keyname, false) ?: new Exception("Error getting attribute ${keyname}");
    }

    /**
     * Get the first argument values.
     *
     * @return mixed
     */
    public function first(callable $callback = null, $default = null)
    {
        return Arr::first($this->values, $callback, $default);
    }

    /**
     * Get the argument values as array.
     *
     * @return array
     */
    public function values(): array
    {
        return $this->values;
    }

    /**
     * Create a new collection instance based on the argument values.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection(): Collection
    {
        return Collection::make($this->values);
    }

    /**
     * Determine if the incoming values are associative array.
     *
     * @return bool
     */
    public function isAssoc(): bool
    {
        return Arr::isAssoc($this->values);
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
        return count($this->values);
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
     * Checks if argument values are empty.
     *
     * @return boolean
     */
    public function isEmpty(): bool
    {
        return $this->values;
    }

    /**
     * Merge the user argument values with defaults data.
     *
     * @return array
     */
    public function data(): array
    {
        $default = $this->getDefaultArgumentValues();

        $values = $this->values();

        if (! (bool) $values && (bool) $default) {
            /// Return the default values, if defined and also,
            /// the client sends null values as arguments.
            return $default;
        } elseif ((bool) $default) {
            /// Some arguments admit values of implicit type, that is, they
            /// can be interpreted by the clause in different ways.
            if (! Arr::isAssoc($values) && $this->hasImplicitValues) {
                /// Prevent exception with "Both parameters should have an equal
                /// number of elements" slicing the values.
                $slice = array_slice(array_keys($default), 0, count($values));

                return array_combine($slice, array_slice($values, 0, count($default)));
            }

            /// Combine the default values with the values sent by the client.
            /// The default values of these attributes are required to be associative.
            return array_merge($default, $values);
        }

        /// Returns the raw data.
        return $values;
    }
}
