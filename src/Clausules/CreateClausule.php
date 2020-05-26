<?php

namespace Restql\Clausules;

use Restql\MutationClausule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class CreateClausule extends MutationClausule
{
    /**
     * The allowed verbs for a determinated clausule.
     *
     * @var array
     */
    protected $allowedVerbs = ['post'];

    /**
     * Implement the clausule query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return void
     */
    public function build(QueryBuilder $builder): void
    {
        $model = $builder->getModel();

        $models = $this->fill($model, $this->arguments->values());

        $ids = $this->transaction($models, function (Model $model) {
            $model->save();
            return $model->getKey();
        });

        $builder->whereIn($model->getKeyName(), $ids);
    }

    /**
     * Get the fillable attributes for the model.
     *
     * @return array
     */
    protected function getFillableAttributes(Model $model): array
    {
        if ($this->hasRestqlAttributesTrait($model)) {
            return $model->onCreateFillables();
        }

        return $model->getFillable();
    }

    /**
     * Throw a exception if can't build this clausule.
     *
     * @return void
     */
    protected function canBuild(): void
    {
        $this->throwIfMethodIsNotAllowed('create clausule');
        // $this->throwIfArgumentIsMissing(class_basename(self::class));
    }
}
