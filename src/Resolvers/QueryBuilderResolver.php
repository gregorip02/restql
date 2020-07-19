<?php

namespace Restql\Resolvers;

use Illuminate\Support\Collection;
use Restql\ClausuleExecutor;
use Restql\Contracts\SchemaHandlerContract;
use Restql\Resolver;
use Restql\SchemaDefinition;
use Restql\Traits\ModelResolver;

final class QueryBuilderResolver extends Resolver implements SchemaHandlerContract
{
    use ModelResolver;

    /**
     * Implement the model|resolver handler.
     *
     * @param  \Restql\SchemaDefinition $schema
     * @return \Illuminate\Support\Collection
     */
    public function handle(SchemaDefinition $schema): Collection
    {
        $builder = ClausuleExecutor::exec($this->getModel($schema), $schema->collect());

        if (! $builder->getQuery()->limit) {
            // Set the "limit" value of the query by default.
            $builder->limit(15);
        }

        return $builder->get();
    }
}
