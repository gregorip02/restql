<?php

namespace Restql\Clausules;

use Restql\Builder;
use Illuminate\Support\Collection;
use Restql\Contracts\ClausuleContract;
use Illuminate\Database\Eloquent\Model;

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
