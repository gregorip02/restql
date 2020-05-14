<?php

namespace Restql;

use Restql\Services\ConfigService;
use Restql\Traits\HasConfigService;

abstract class Resolver
{
    use HasConfigService;
}
