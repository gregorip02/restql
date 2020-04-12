<?php

namespace Restql\Clausules;

use Illuminate\Support\Collection;
use Restql\ClausuleExecutor;
use Restql\Contracts\ClausuleContract;
use Restql\Exceptions\InvalidClausuleArgument;

class SortClausule implements ClausuleContract
{
    /**
     * {@inheritdoc}
     */
    public function build(ClausuleExecutor $executor, Collection $arguments): void
    {
        $executor->executeQuery(function ($query) use ($arguments) {
            $arguments = $arguments->slice(0, 2)->toArray();

            if (count($arguments) == 2) {
                [$column, $direction] = $arguments;

                /// Checks the orderBy direction argument.
                if (!in_array($direction, ['desc', 'asc'])) {
                    throw new InvalidClausuleArgument('desc or asc', (string) $direction);
                }
            } else {
                $column = $arguments[0];
                $direction = 'asc';
            }

            $query->orderBy($column, $direction);
        });
    }
}
