<?php

namespace Restql\Clausules;

use Restql\Clausule;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class WhereClausule extends Clausule
{
    public $rules = [
        'column'   => ['required'],
        'operator' => ['sometimes', 'nullable'],
        'value'    => ['sometimes', 'nullable']
    ];

    /**
     * Implement the clausule query builder.
     *
     * @return void
     */
    public function build(): void
    {
        $this->executor->executeQuery(function (QueryBuilder $builder) {
            $builder->where(...$this->getQueryArguments());
        });
    }

    /**
     * Get the parsed validation data.
     *
     * @return array
     */
    public function getValidatorData(): array
    {
        $arguments = $this->arguments->toArray();

        if (!array_key_exists('column', $arguments)) {
            $arguments['column'] = $arguments[0];
        }

        if (!array_key_exists('value', $arguments)) {
            $arguments['value'] = $arguments[1];
        }

        return $arguments;
    }
}
