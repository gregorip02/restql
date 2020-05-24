<?php

namespace Restql\Clausules;

use Restql\Argument;
use Restql\Clausule;
use Restql\Arguments\SortArgument;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class SortClausule extends Clausule
{
    /**
     * The allowed verbs for a determinated clausule.
     *
     * @var array
     */
    protected $allowedVerbs = ['all'];

    /**
     * Implement the clausule query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return void
     */
    public function build(QueryBuilder $builder): void
    {
        $arguments = array_values($this->arguments->data());

        $builder->orderBy(...$arguments);
    }

    /**
     * Get a new instance of the clausule argument.
     *
     * @param  array  $values
     * @return \Restql\Arguments\WhereArgument
     */
    protected function createArgumentsInstance(array $values = []): Argument
    {
        return new SortArgument($this->executor->getModel(), $values);
    }


    /**
     * Throw a exception if can't build this clausule.
     *
     * @return void
     */
    protected function canBuild(): void
    {
        parent::throwIfMethodIsNotAllowed('sort');
    }
}
