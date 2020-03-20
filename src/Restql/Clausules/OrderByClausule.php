<?php

namespace Restql\Clausules;

use Restql\Builder;
use Illuminate\Support\Collection;
use Restql\Contracts\ClausuleContract;

class OrderByClausule implements ClausuleContract
{
    /**
     * {@inheritdoc}
     */
    public function build(Builder $builder, Collection $attributes): void
    {
        $builder->executeQuery(function ($query) use ($attributes) {
            $query->orderBy(...$attributes->slice(0, 2));
        });
    }
}
