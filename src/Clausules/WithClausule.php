<?php

namespace App\RestQL\Clausules;

use App\RestQL\Builder;
use App\RestQL\Contracts\ClausuleContract;

class WithClausule implements ClausuleContract
{
    /**
     * Get the clausule arguments.
     *
     * @param  App\RestQL\Builder $builder
     * @param  array|string $constrains
     * @return array
     */
    public function args(Builder $builder, $constrains)
    {
        return dd($constrains);
    }
}
