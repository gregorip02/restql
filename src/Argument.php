<?php

namespace Restql;

use Illuminate\Support\Collection;

class Argument
{
    /**
     * The argument values
     *
     * @var \Illuminate\Support\Collection
     */
    protected $values;

    /**
     * The argument default keys.
     *
     * @var array
     */
    public $keys = [];

    /**
     * The argument default values.
     *
     * @var array
     */
    public $defaults = [];

    /**
     *
     * @param Collection $values [description]
     */
    public function __construct(Collection $values)
    {
        $this->values = $values;

        $this->fillDefaultValues();
    }

    /**
     * Get the argument values as array.
     *
     * @return array
     */
    public function values(): array
    {
        return $this->values->toArray();
    }

    /**
     * Determine if the values are associative array.
     *
     * @return bool
     */
    protected function isAssociative(): bool
    {
        return ! isset($this->values()[0]);
    }

    /**
     * Merge default values into argument keys.
     *
     * @return array
     */
    protected function combineAssociativeValues(): array
    {
        return array_combine($this->keys, $this->defaults);
    }

    /**
     * Merge defaults values into incoming indexed values.
     *
     * @return array
     */
    protected function combineIndexedValues(): array
    {
        $values = $this->values();

        $countDefaults = $this->countDefaults();

        $data = array_merge(
            $values,
            array_slice($this->defaults, count($values), $countDefaults)
        );

        return array_slice($data, 0, $countDefaults);
    }

    /**
     * Fill the default values list with some value.
     *
     * @param  mixed $value
     *
     * @return void
     */
    protected function fillDefaultValues($value = null): void
    {
        $countDefaults = $this->countDefaults();

        $countKeys = $this->countKeys();

        if ($countDefaults < $countKeys) {
            /// Fill the defaults values with null because the
            /// parameters should have an equal number of elements for combine.
            for ($i = $countDefaults; $i < $countKeys; $i++) {
                $this->defaults[$i] = $value;
            }
        } elseif ($countDefaults > $countKeys) {
            /// Crop the defaults values because the parameters should have an
            /// equals number of elements.
            $this->defaults = array_slice($this->defaults, 0, $countKeys);
        }
    }

    /**
     * Count the default argument values.
     *
     * @return int
     */
    public function countDefaults(): int
    {
        return count($this->defaults);
    }

    /**
     * Count the default argument keys.
     *
     * @return int
     */
    public function countKeys(): int
    {
        return count($this->keys);
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
     * Merge the user argument values with defaults data.
     *
     * @return array
     */
    public function data(): array
    {
        if (! $this->isAssociative()) {
            $this->defaults = $this->combineIndexedValues();

            /// The default values are now the user values because
            /// these are not associative data. Then, we will return
            /// the default merge of these data.
            return $this->combineAssociativeValues();
        }

        return array_merge($this->combineAssociativeValues(), $this->values());
    }
}
