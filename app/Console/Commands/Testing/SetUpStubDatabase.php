<?php

namespace App\Console\Commands\Testing;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class SetUpStubDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testing:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set Up Stub Database';

    /** @var Filesystem */
    protected $files;

    protected $stubDbPath = '';

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->stubDbPath = config('database.testing.stubdb');
        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ( $this->files->exists($this->stubDbPath) ) {
            $this->files->delete($this->stubDbPath);
        }
        $this->files->put($this->stubDbPath, '');

        \Artisan::call('migrate:refresh', ['--seed' => true, '--database' => 'setup', '--env' => 'testing']);
    }

}
