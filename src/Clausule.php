<?php

namespace Restql;

use Restql\ClausuleExecutor;
use Restql\Exceptions\InvalidClausuleArgumentException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

abstract class Clausule
{
    /**
     * The clausule executor.
     *
     * @var \Restql\ClausuleExecutor
     */
    protected $executor;

    /**
     * The clausule arguments.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $arguments;

    /**
     * The argument validation rules.
     *
     * @var array
     */
    public $rules = [];

    /**
     * The validated data.
     *
     * @var array
     */
    public $validated = [];

    /**
     * The class instance.
     *
     * @param \Restql\ClausuleExecutor $executor
     * @param \Illuminate\Support\Collection $arguments
     */
    public function __construct(ClausuleExecutor $executor, Collection $arguments)
    {
        $this->executor = $executor;
        $this->arguments = $arguments;

        /// Validate the incoming arguments
        $this->validate();
    }

    /**
     * Validate your incoming arguments.
     *
     * @return void
     */
    protected function validate(): void
    {
        if (count($this->rules)) {
            $validator = Validator::make($this->getValidatorData(), $this->rules);

            if ($validator->fails()) {
                throw new InvalidClausuleArgumentException();
            }

            $this->validated = $validator->validated();
        }
    }

    /**
     * Generic getter for the query arguments.
     *
     * @return array
     */
    public function getQueryArguments(): array
    {
        return array_values($this->validated);
    }

    /**
     * Get the validator data.
     *
     * @return array
     */
    public function getValidatorData(): array
    {
        return $this->arguments->toArray();
    }

    /**
     * Implement the clausule query builder.
     *
     * @return void
     */
    abstract public function build(): void;
}
