<?php

namespace App\RestQL\Clausules;

use App\RestQL\Builder;
use App\RestQL\Contracts\ClausuleContract;

class OrderByClausule implements ClausuleContract
{
    /**
     * Get the clausule arguments.
     *
     * @param  App\RestQL\Builder $builder
     * @param  string $constrains
     * @return string
     */
    public function args(Builder $builder, $constrains)
    {
        return collect($constrains)->first();
    }
}
