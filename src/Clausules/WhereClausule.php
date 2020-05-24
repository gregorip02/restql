<?php

namespace Restql\Clausules;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Restql\Argument;
use Restql\Arguments\WhereArgument;
use Restql\Clausule;
use Restql\Exceptions\MissingArgumentException;

class WhereClausule extends Clausule
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
     *
     * @return void
     */
    public function build(QueryBuilder $builder): void
    {
        $args = array_values($this->arguments->data());

        $builder->where(...$args);
    }

    /**
     * Get a new instance of the clausule argument.
     *
     * @param  array  $values
     * @return \Restql\Arguments\WhereArgument
     */
    protected function createArgumentsInstance(array $values = []): Argument
    {
        return new WhereArgument($this->executor->getModel(), $values);
    }

    /**
     * Has argument missing hook.
     *
     * @param  string $class
     * @throws Exception
     */
    protected function throwIfArgumentIsMissing(string $class): void
    {
        if ($this->arguments->isAssoc() && !$this->arguments->getAttribute('value', false)) {
            throw new MissingArgumentException('value', $class);
        }
    }

    /**
     * Throw a exception if can't build this clausule.
     *
     * @return void
     */
    protected function canBuild(): void
    {
        parent::throwIfMethodIsNotAllowed('where');
        $this->throwIfArgumentIsMissing('where');
    }
}
