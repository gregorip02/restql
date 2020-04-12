<?php

namespace Restql\Contracts;

use Restql\ClausuleExecutor;
use Illuminate\Support\Collection;

interface ClausuleContract
{
    /**
     * Build the clausule.
     *
     * @param \Restql\ClausuleExecutor $executor
     * @param \Illuminate\Support\Collection $arguments
     * @return void
     */
    public function build(ClausuleExecutor $executor, Collection $arguments): void;
}
