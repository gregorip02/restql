<?php

namespace Restql\Clausules;

use Restql\Argument;
use Restql\Clausules\WhereClausule;
use Restql\Arguments\WhereInOrNotInArgument;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class WhereInClausule extends WhereClausule
{
    /**
     * Implement the clausule query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return void
     */
    public function build(QueryBuilder $builder): void
    {
        $args = array_values($this->arguments->data());

        $builder->whereIn(...$args);
    }

    /**
     * Get a new instance of the clausule argument.
     *
     * @param  array  $values
     * @return \Restql\Arguments\WhereArgument
     */
    protected function createArgumentsInstance(array $values = []): Argument
    {
        return new WhereInOrNotInArgument($this->executor->getModel(), $values);
    }

    /**
     * Throw a exception if can't build this clausule.
     *
     * @return void
     */
    protected function canBuild(): void
    {
        parent::throwIfMethodIsNotAllowed('whereIn');
        $this->throwIfArgumentIsMissing('whereIn');
    }
}
