<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class UpdateAssetHash extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asset:update-hash';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update asset version hash';

    /** @var Filesystem */
    protected $files;

    protected $type = '';

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $configPath = $this->getConfigFilePath();
        $config = $this->files->get($configPath);
        $newVersionNumber = $this->getNewVersionNumber();
        $newConfig = $this->replaceVersionNumber($config, $newVersionNumber);
        echo "new version: $newVersionNumber".PHP_EOL;
        $this->files->put($configPath, $newConfig);
    }

    /**
     * @return string
     */
    private function getConfigFilePath()
    {
        return $this->laravel['path.config'].'/asset.php';
    }

    /**
     * @return string
     */
    private function getNewVersionNumber()
    {
        return \DateTimeHelper::now()->format('U');
    }

    private function replaceVersionNumber($file, $version)
    {
        $file = preg_replace('/\'\d{10}\'/', "'$version'", $file);

        return $file;
    }
}
