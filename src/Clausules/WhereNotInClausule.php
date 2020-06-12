<?php

namespace Restql\Clausules;

use Restql\Clausules\WhereInClausule;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class WhereNotInClausule extends WhereInClausule
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

        $builder->whereNotIn($args[0], (array) $args[1]);
    }
}
