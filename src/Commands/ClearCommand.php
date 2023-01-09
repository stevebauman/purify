<?php

namespace Stevebauman\Purify\Commands;

use Illuminate\Config\Repository;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ClearCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'purify:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush the HTML purifier serializer cache';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(Repository $config, Filesystem $files)
    {
        if (empty($path = $config->get('purify.serializer'))) {
            return $this->error(
                'Purifier serializer path is not defined. Did you set it to null or forget to publish the configuration?'
            );
        }

        $files->cleanDirectory($path);

        $this->info('HTML Purifier serializer cache cleared successfully.');
    }
}
