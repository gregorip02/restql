<?php

namespace Restql;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Restql\Argument;
use Restql\ClausuleExecutor;
use Restql\Exceptions\InvalidClausuleArgumentException;

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
     * Prepare the clausule builder.
     *
     * @return void
     */
    public function prepare(): void
    {
        $this->executor->executeQuery(function (QueryBuilder $builder) {
            $this->build($builder);
        });
    }

    /**
     * Validate your incoming arguments.
     *
     * @return void
     */
    protected function validate(): void
    {
        $validator = Validator::make($this->getValidatorData(), $this->rules);

        if ($validator->fails()) {
            throw new InvalidClausuleArgumentException('Validation failed');
        }

        $this->validated = $validator->validated();
    }

    /**
     * Get the validation data.
     *
     * @return array
     */
    public function getValidatorData(): array
    {
        return $this->getArgumentInstance()->data();
    }

    /**
     * Get the clausule validated data.
     */
    public function getValidatedData(): array
    {
        $values = count($this->rules) ?
            $this->validated : $this->getValidatorData();

        return array_values($values);
    }

    /**
     * Get the clausule arguments.
     *
     * @return \Restql\Argument
     */
    public function getArgumentInstance(): Argument
    {
        return new Argument($this->arguments);
    }

    /**
     * Implement the clausule query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return void
     */
    abstract public function build(QueryBuilder $builder): void;
}
