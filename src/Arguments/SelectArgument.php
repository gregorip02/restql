<?php

namespace Restql\Arguments;

use Illuminate\Support\Collection;
use Restql\Contracts\ArgumentContract;
use Restql\ModelArgument;

class SelectArgument extends ModelArgument implements ArgumentContract
{
    /**
     * Get the argument values as array.
     *
     * @return array
     */
    public function values(): array
    {
        $values = $this->excludeHiddenAttributes();

        /// The onSelectFillables method allow to developers to determine
        /// what attributes can be obtained for clients based on
        /// your roles or pemissions.
        if ($this->unsingRestqlTrait()) {
            $values = Collection::make(array_combine($values, $values))
                      ->only($this->model->onSelectFillables())
                      ->keys()
                      ->toArray();
        }

        /// By default we include the model primary key on every
        /// select clausule because is necesary for add correctly
        /// the model releationships eager loaded.
        $primaryKeyName = $this->getKeyName();

        if (! in_array($primaryKeyName, $values)) {
            $values[] = $primaryKeyName;
        }

        return $values;
    }
}
