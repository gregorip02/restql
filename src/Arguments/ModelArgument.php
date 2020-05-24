<?php

namespace Restql\Arguments;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
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
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array $values
     */
    public function __construct(Model $model, array $values = [])
    {
        $this->model  = $model;
        $this->values = $values;
    }

    /**
     * Get argument model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel(): Model
    {
        return $this->model;
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

    /**
     * Get the hidden attributes for the model.
     *
     * @return array
     */
    public function getHidden(): array
    {
        return $this->model->getHidden();
    }

    /**
     * Exclude model hidden attributes.
     *
     * @return array
     */
    public function excludeHiddenAttributes(): array
    {
        $hidden = $this->getHidden();

        return array_filter(parent::values(), function ($value) use ($hidden) {
            return ! in_array($value, $hidden, true);
        });
    }
}
