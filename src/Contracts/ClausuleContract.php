<?php

namespace Restql\Contracts;

use Restql\ClausuleExecutor;
use Illuminate\Support\Collection;

interface ClausuleContract
{
    /**
     * Get the clausule arguments.
     *
     * @param \Restql\ClausuleExecutor $executor
     * @param \Illuminate\Support\Collection $arguments
     * @return void
     */
    public function build(ClausuleExecutor $executor, Collection $arguments): void;
}
