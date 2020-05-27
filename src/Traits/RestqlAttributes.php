<?php

namespace Restql\Traits;

trait RestqlAttributes
{
    /**
     * Get the fillable attributes for creations.
     *
     * @return array
     */
    public function onCreateFillables(): array
    {
        return $this->getFillable();
    }

    /**
     * Get the fillable attributes for updates.
     *
     * @return array
     */
    public function onUpdateFillables(): array
    {
        return $this->getFillable();
    }
}
