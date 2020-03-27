<?php

namespace Restql\Clausules;

use Restql\ClausuleExecutor;
use Illuminate\Support\Collection;
use Restql\Contracts\ClausuleContract;

class OrderByClausule implements ClausuleContract
{
    /**
     * {@inheritdoc}
     */
    public function build(ClausuleExecutor $executor, Collection $arguments): void
    {
        $executor->executeQuery(function ($query) use ($arguments) {
            $query->orderBy(...$arguments->slice(0, 2));
        });
    }
}
