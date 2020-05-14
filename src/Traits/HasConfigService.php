<?php

namespace Restql\Traits;

use Restql\Services\ConfigService;

trait HasConfigService
{
    /**
     * Get the config service instance.
     *
     * @return \Restql\Services\ConfigService
     */
    public function getConfigService(): ConfigService
    {
        return app(ConfigService::class);
    }
}
