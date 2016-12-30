<?php

namespace App\Console\Commands\Testing;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class Execute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testing:exec';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Unit text';

    /** @var Filesystem */
    protected $files;

    /** @var mixed|string  */
    protected $testDbPath = '';

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->testDbPath = config('database.testing.testdb');
        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ( $this->files->exists($this->testDbPath) ) {
            $this->files->delete($this->testDbPath);
        }

        system(base_path('vendor/bin/phpunit'));

        if ( $this->files->exists($this->testDbPath) ) {
            $this->files->delete($this->testDbPath);
        }
    }

}
