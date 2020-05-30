<?php

namespace Restql\Console;

use Illuminate\Console\GeneratorCommand;

final class ResolverMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'restql:resolver';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new RestQL resolver class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Resolver';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../../stubs/resolver.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\Restql\\Resolvers';
    }
}
