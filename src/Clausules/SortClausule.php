<?php

namespace Restql\Clausules;

use Restql\Argument;
use Restql\Clausule;
use Restql\Arguments\SortArgument;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class SortClausule extends Clausule
{
    /**
     * The argument validation rules.
     *
     * @var array
     */
    public $rules = [
        'column'    => ['required', 'string'],
        'direction' => ['sometimes', 'in:desc,asc']
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
        $builder->orderBy(...$this->getValidatedData());
    }

    /**
     * Get the clausule arguments.
     *
     * @return \Restql\Arguments\WhereArgument
     */
    public function getArgumentInstance(): Argument
    {
        return new SortArgument($this->arguments);
    }
}
