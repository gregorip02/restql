<?php

namespace Restql\Arguments;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Restql\Argument;

abstract class ModelArgument extends Argument
{
    /**
     * Get the argument parent model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Class instance.
     *
     * @param \Illuminate\Support\Collection $values
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function __construct(Collection $values, Model $model)
    {
        $this->values = $values;
        $this->model = $model;
    }

    /**
     * Get the primary key for the model.
     *
     * @return string
     */
    public function getKeyName(): string
    {
        return $this->model->getKeyName();
    }
}
