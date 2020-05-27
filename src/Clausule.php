<?php

namespace Restql;

use Restql\Argument;
use Restql\ClausuleExecutor;
use Restql\Exceptions\AccessDeniedHttpException;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

abstract class Clausule
{
    /**
     * The clausule executor.
     *
     * @var \Restql\ClausuleExecutor
     */
    protected $executor;

    /**
     * The clausule argument.
     *
     * @var \Restql\Argument
     */
    protected $arguments;

    /**
     * The allowed verbs for a determinated clausule.
     *
     * @var array
     */
    protected $allowedVerbs = [];

    /**
     * The class instance.
     *
     * @param \Restql\ClausuleExecutor $executor
     * @param array $arguments
     */
    public function __construct(ClausuleExecutor $executor, array $arguments = [])
    {
        $this->executor  = $executor;
        $this->arguments = $this->createArgumentsInstance($arguments);
        $this->canBuild();
    }

    /**
     * Prepare the clausule builder.
     *
     * @return void
     */
    public function prepare(): void
    {
        $this->executor->executeQuery(function (QueryBuilder $builder) {
            $this->build($builder);
        });
    }

    /**
     * Get the clausule arguments instance.
     *
     * @return \Restql\Argument
     */
    public function arguments(): Argument
    {
        return $this->arguments;
    }

    /**
     * Get a new instance of the clausule argument.
     *
     * @param  array  $values
     * @return \Restql\Argument
     */
    protected function createArgumentsInstance(array $values = []): Argument
    {
        return new Argument($values);
    }

    /**
     * Returns the request access method for a determinated clausule.
     *
     * @return string
     */
    protected function accessMethod(): string
    {
        $method = request()->method();

        return Str::lower($method);
    }

    /**
     * Determine if the incoming request method is allowed for a determinated clausule.
     *
     * @return boolean
     */
    protected function isAllowedMethod(): bool
    {
        $method = $this->accessMethod();

        return in_array($method, $this->allowedVerbs) || in_array('all', $this->allowedVerbs);
    }

    /**
     * Has method not allowed hook.
     *
     * @param  string $name
     * @throws \Restql\Exceptions\AccessDeniedHttpException
     */
    protected function throwIfMethodIsNotAllowed(string $name): void
    {
        if (! $this->isAllowedMethod()) {
            throw new AccessDeniedHttpException($name, $this->accessMethod());
        }
    }

    /**
     * Has argument missing hook.
     *
     * @param  string $class
     * @throws Exception
     */
    protected function throwIfArgumentIsMissing(string $class): void
    {
        //
    }

    /**
     * Throw a exception if can't build this clausule.
     *
     * @return void
     */
    protected function canBuild(): void
    {
        $this->throwIfMethodIsNotAllowed(class_basename(self::class));
        // $this->throwIfArgumentIsMissing(class_basename(self::class));
    }

    /**
     * Implement the clausule query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return void
     */
    abstract public function build(QueryBuilder $builder): void;
}
