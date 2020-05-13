<?php

namespace Restql\Clausules;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Restql\Argument;
use Restql\Clausule;

class TakeClausule extends Clausule
{
    /**
     * Implement the clausule query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return void
     */
    public function build(QueryBuilder $builder): void
    {
        $builder->take($this->arguments->first());
    }
}
