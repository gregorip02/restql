<?php

namespace Restql\Contracts;

use Restql\SchemaDefinition;
use Illuminate\Support\Collection;

interface SchemaHandlerContract
{
    /**
     * Implement the model|resolver handler.
     *
     * @param  \Restql\SchemaDefinition $schema
     * @return \Illuminate\Support\Collection
     */
    public function handle(SchemaDefinition $schema): Collection;
}
