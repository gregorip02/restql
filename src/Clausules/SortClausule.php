<?php

namespace Restql\Clausules;

use Restql\Clausule;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class SortClausule extends Clausule
{
    /**
     * The argument validation rules.
     *
     * @var array
     */
    public $rules = [
        'column'    => ['required', 'string'],
        'direction' => ['sometimes', 'in:desc,asc']
    ];

    /**
     * Implement the clausule query builder.
     *
     * @return void
     */
    public function build(): void
    {
        $this->executor->executeQuery(function (QueryBuilder $builder) {
            $builder->orderBy(...$this->getQueryArguments());
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
            $arguments['direction'] = $arguments[1] ?? 'asc';
        }

        return $arguments;
    }
}
