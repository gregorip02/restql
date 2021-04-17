<?php

namespace Restql\Contracts;

use Illuminate\Support\Collection;
use Restql\SchemaDefinition;

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
