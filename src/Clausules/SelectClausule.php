<?php

namespace App\RestQL\Clausules;

use App\RestQL\Builder;
use App\RestQL\Contracts\ClausuleContract;

class SelectClausule implements ClausuleContract
{
    /**
     * Get the clausule arguments.
     *
     * @param  App\RestQL\Builder $builder
     * @param  array|string $constrains
     * @return array
     */
    public function args(Builder $builder, $constrains): array
    {
        $hidden = $builder->parentModel()->getHidden();

        return collect($constrains)->filter(function ($value, $key) use ($hidden) {
            return is_numeric($key) && !in_array($value, $hidden);
        })
        // Add the primary key name for every select.
        ->add($builder->parentModelKeyName())->unique()->toArray();
    }
}
