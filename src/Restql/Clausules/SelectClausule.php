<?php

namespace Restql\Clausules;

use Restql\Builder;
use Illuminate\Support\Collection;
use Restql\Contracts\ClausuleContract;
use Illuminate\Database\Eloquent\Model;

class SelectClausule implements ClausuleContract
{
    /**
     * {@inheritdoc}
     */
    public function build(Builder $builder, Collection $attributes): void
    {
        $builder->executeQuery(function ($query) use ($attributes) {
            $model = $query->getModel();
            // Excecute the select with the model non-hidden attributes
            // only.
            $query->select($this->attrs($model, $attributes));
        });
    }

    /**
     * Get the select attributes.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param \Illuminate\Support\Collection $attributes
     * @return array
     */
    public function attrs(Model $model, Collection $attributes): array
    {
        $hidden = $model->getHidden();

        return $attributes->filter(function ($value, $key) use ($hidden) {
            // Don't include hiddens attributes or associative values
            // on the select.
            return is_numeric($key) && !in_array($value, $hidden);
        })
        // Add the primary key name for every select.
        ->add($model->getKeyName())->unique()->toArray();
    }
}
