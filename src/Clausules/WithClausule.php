<?php

namespace Restql\Clausules;

use Restql\Clausule;
use Restql\Argument;
use Restql\Arguments\WithArgument;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class WithClausule extends Clausule
{
    /**
     * The allowed verbs for a determinated clausule.
     *
     * @var array
     */
    protected $allowedVerbs = ['get'];

    /**
     * Implement the clausule query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return void
     */
    public function build(QueryBuilder $builder): void
    {
        $builder->with($this->arguments->data());
    }

    /**
     * Get a new instance of the clausule argument.
     *
     * @param  array  $values
     * @return \Restql\Arguments\WhereArgument
     */
    protected function createArgumentsInstance(array $values = []): Argument
    {
        return new WithArgument($this->executor->getModel(), $values);
    }

    /**
     * Throw a exception if can't build this clausule.
     *
     * @return void
     */
    protected function canBuild(): void
    {
        parent::throwIfMethodIsNotAllowed('where');
    }
}
