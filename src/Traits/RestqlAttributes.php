<?php

namespace Restql\Traits;

trait RestqlAttributes
{
    /**
     * Get the fillable attributes for selects.
     *
     * @return array
     */
    public function onSelectFillables(): array
    {
        return $this->getFillable();
    }
}
