<?php

namespace App\RestQL\Contracts;

use App\RestQL\Builder;

interface ClausuleContract
{
    /**
     * Get the clausule arguments.
     *
     * @param  App\RestQL\Builder $builder
     * @param  array|string $constrains
     * @return array|string
     */
    public function args(Builder $builder, $constrains);
}
