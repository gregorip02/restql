<?php

namespace Restql\Traits;

trait RestqlAttributes
{
    /**
     * Get the fillable attributes for the model.
     *
     * @return array
     */
    public function onCreateFillables(): array
    {
        return $this->getFillable();
    }

    /**
     * Get the fillable attributes for the model.
     *
     * @return array
     */
    public function onUpdateFillables(): array
    {
        return $this->getFillable();
    }
}
