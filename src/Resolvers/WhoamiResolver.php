<?php

namespace Restql\Resolvers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Restql\Contracts\SchemaHandlerContract;
use Restql\Resolver;
use Restql\SchemaDefinition;

final class WhoamiResolver extends Resolver implements SchemaHandlerContract
{
    /**
     * Implement the model|resolver handler.
     *
     * @param  \Restql\SchemaDefinition $schema
     * @return \Illuminate\Support\Collection
     */
    public function handle(SchemaDefinition $schema): Collection
    {
        $user = Auth::user();

        return Collection::make($user);
    }
}
