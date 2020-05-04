<?php

namespace Restql\Clausules;

use Restql\Clausule;
use Restql\Argument;
use Restql\Arguments\WhereArgument;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class WhereClausule extends Clausule
{
    /**
     * The arguments rules.
     *
     * @var array
     */
    public $rules = [
        'column'   => ['required'],
        'operator' => ['sometimes', 'nullable'],
        'value'    => ['sometimes', 'nullable']
    ];

    /**
     * Implement the clausule query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return void
     */
    public function build(QueryBuilder $builder): void
    {
        $builder->where(...$this->getValidatedData());
    }

    /**
     * Get the clausule arguments.
     *
     * @return \Restql\Arguments\WhereArgument
     */
    public function getArgumentInstance(): Argument
    {
        return new WhereArgument($this->arguments, $this->executor->getModel());
    }
}
