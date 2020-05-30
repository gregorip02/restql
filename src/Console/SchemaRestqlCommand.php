<?php

namespace Restql\Console;

use Illuminate\Console\Command;

final class SchemaRestqlCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restql:schema';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish the package configuration';

    /**
     * Handle the command.
     *
     * @return void
     */
    public function handle()
    {
        $this->call('vendor:publish', [
            '--tag' => 'restql-config'
        ]);
    }
}
