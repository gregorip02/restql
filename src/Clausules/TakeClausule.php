<?php

namespace Restql\Clausules;

use Restql\Clausule;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class TakeClausule extends Clausule
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
     * @return void
     */
    public function build(QueryBuilder $builder): void
    {
        $builder->take($this->arguments->first(null, 15));
    }

    /**
     * Throw a exception if can't build this clausule.
     *
     * @return void
     */
    protected function canBuild(): void
    {
        parent::throwIfMethodIsNotAllowed('take');
    }
}
