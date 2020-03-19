<?php

namespace Restql\Contracts;

use Restql\Builder;
use Illuminate\Support\Collection;

interface ClausuleContract
{
    /**
     * Get the clausule arguments.
     *
     * @param \Restql\Builder
     * @param \Illuminate\Support\Collection
     * @return void
     */
    public function build(Builder $builder, Collection $arguments): void;
}
