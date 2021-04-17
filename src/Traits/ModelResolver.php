<?php

namespace Restql\Traits;

use Illuminate\Database\Eloquent\Model;
use Restql\SchemaDefinition;

trait ModelResolver
{
    /**
     * Returns the schema model instance.
     *
     * @param  \Restql\SchemaDefinition $schema
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel(SchemaDefinition $schema): Model
    {
        $classname = $schema->getClass();

        return new $classname();
    }
}
