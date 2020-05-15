<?php

namespace Restql\Resolvers;

use Illuminate\Support\Arr;
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
        $builder = ClausuleExecutor::exec(
            $this->getModel($schema),
            Collection::make($schema->getArguments())
        );

        $limit = $builder->getQuery()->limit ?? 15;

        return $builder->take($limit)->get();
    }
}