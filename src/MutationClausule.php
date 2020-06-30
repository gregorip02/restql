<?php

namespace Restql;

use Closure;
use Restql\Clausule;
use Restql\Traits\RestqlAttributes;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

abstract class MutationClausule extends Clausule
{
    /**
     * Determines if the current model use the RestqlAttributes trait.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return boolean
     */
    protected function hasRestqlAttributesTrait(Model $model): bool
    {
        return in_array(RestqlAttributes::class, class_uses($model));
    }

    /**
     * Create a collection of models and fill it with an array of attributes.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  array $attributes
     * @return \Illuminate\Support\Collection
     */
    public function fill(Model $model, array $attributes): Collection
    {
        if (Arr::isAssoc($attributes)) {
            return $this->fill($model, [$attributes]);
        }

        return Collection::make($attributes)->map(function ($values) use ($model) {
            return $model->newInstance()
                ->fillable($this->getFillableAttributes($model))->fill($values);
        });
    }

    /**
     * Execute a Closure within a transaction and inject the actual model.
     *
     * @param  \Illuminate\Support\Collection $models
     * @param  \Closure $callback
     * @param  int|integer $attempts
     * @return array
     */
    public function transaction(Collection $models, Closure $callback, int $attempts = 2): array
    {
        return DB::transaction(function () use ($models, $callback) {
            return $models->map(function (Model $model) use ($callback) {
                return $callback($model);
            })->toArray();
        }, $attempts);
    }

    /**
     * Get the fillable attributes for the model.
     *
     * @return array
     */
    abstract protected function getFillableAttributes(Model $model): array;
}
