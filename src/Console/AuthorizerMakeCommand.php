<?php

namespace Restql\Console;

use Illuminate\Console\GeneratorCommand;

final class AuthorizerMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'restql:authorizer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new RestQL authorizer class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Authorizer';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../../stubs/authorizer.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\Restql\\Authorizers';
    }
}
