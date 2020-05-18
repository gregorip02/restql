<?php

namespace Restql\Clausules;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Closure;

class CreateClausule extends MutationClausule
{
    /**
     * Implement the clausule query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return void
     */
    public function build(QueryBuilder $builder): void
    {
        $model = $builder->getModel();

        $models = $this->fill($model, $this->arguments->toArray());

        $ids = $this->transaction($models, function (Model $model) {
            $model->save();
            return $model->getKey();
        });

        $builder->whereIn($model->getKeyName(), $ids);
    }

    /**
     * [getFillableAttributes description]
     * @param  Model  $model [description]
     * @return [type]        [description]
     */
    protected function getFillableAttributes(Model $model): array
    {
        if ($this->hasRestqlAttributesTrait($model)) {
            return $model->onCreateFillables();
        }

        return $model->getFillable();
    }
}
