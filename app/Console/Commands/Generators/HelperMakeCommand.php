<?php

namespace App\Console\Commands\Generators;

class HelperMakeCommand extends GeneratorCommandBase
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:helper';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new helper class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Helper';

    protected function generate($name)
    {
        if (!$this->generateInterface($name)) {
            return false;
        }
        if (!$this->generateHelper($name)) {
            return false;
        }
        if (!$this->generateFacade($name)) {
            return false;
        }
        if (!$this->generateUnitTest($name)) {
            return false;
        }
        if (!$this->addFacadeToConfig($name)) {
            return false;
        }

        return $this->bindInterface($name);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function generateInterface($name)
    {
        $path = $this->getInterfacePath($name);
        if ($this->alreadyExists($path)) {
            $this->error($name.' already exists.');

            return false;
        }

        $this->makeDirectory($path);

        $className = $this->getClassName($name);

        $stub = $this->files->get($this->getStubForInterface());
        $this->replaceTemplateVariable($stub, 'CLASS', $className);
        $this->files->put($path, $stub);

        return true;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function generateHelper($name)
    {
        $path = $this->getHelperPath($name);
        if ($this->alreadyExists($path)) {
            $this->error($name.' already exists.');

            return false;
        }

        $this->makeDirectory($path);

        $className = $this->getClassName($name);

        $stub = $this->files->get($this->getStubForHelper());
        $this->replaceTemplateVariable($stub, 'CLASS', $className);
        $this->files->put($path, $stub);

        return true;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function generateFacade($name)
    {
        $path = $this->getFacadePath($name);
        if ($this->alreadyExists($path)) {
            $this->error($name.' already exists.');

            return false;
        }

        $this->makeDirectory($path);

        $className = $this->getClassName($name);

        $stub = $this->files->get($this->getStubForFacade());
        $this->replaceTemplateVariable($stub, 'CLASS', $className);
        $this->files->put($path, $stub);

        return true;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function generateUnitTest($name)
    {
        $path = $this->getUnitTestPath($name);
        if ($this->alreadyExists($path)) {
            $this->error($name.' already exists.');

            return false;
        }

        $this->makeDirectory($path);

        $className = $this->getClassName($name);

        $stub = $this->files->get($this->getStubForUnitTest());
        $this->replaceTemplateVariable($stub, 'CLASS', $className);
        $this->files->put($path, $stub);

        return true;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function bindInterface($name)
    {
        $className = $this->getClassName($name);

        $bindService = $this->files->get($this->getBindServiceProviderPath());
        $key = '/* NEW BINDING */';
        $bind = '$this->app->singleton('.PHP_EOL."            \\App\\Helpers\\".$className."Interface::class,".PHP_EOL."            \\App\\Helpers\\Production\\".$className."::class".PHP_EOL.'        );'.PHP_EOL.PHP_EOL.'        '.$key;
        $bindService = str_replace($key, $bind, $bindService);
        $this->files->put($this->getBindServiceProviderPath(), $bindService);

        return true;
    }

    protected function addFacadeToConfig($name)
    {
        $className = $this->getClassName($name);

        $appConfig = $this->files->get($this->getAppConfigPath());
        $key = '/* NEW FACADE */';
        $facade = "'".$className."'  => \\App\\Facades\\".$className.'::class,'.PHP_EOL.'        '.$key;
        $appConfig = str_replace($key, $facade, $appConfig);
        $this->files->put($this->getAppConfigPath(), $appConfig);

        return true;
    }

    protected function getInterfacePath($name)
    {
        $className = $this->getClassName($name);

        return $this->laravel['path'].'/Helpers/'.$className.'Interface.php';
    }

    protected function getHelperPath($name)
    {
        $className = $this->getClassName($name);

        return $this->laravel['path'].'/Helpers/Production/'.$className.'.php';
    }

    protected function getFacadePath($name)
    {
        $className = $this->getClassName($name);

        return $this->laravel['path'].'/Facades/'.$className.'.php';
    }

    protected function getUnitTestPath($name)
    {
        $className = $this->getClassName($name);

        return $this->laravel['path'].'/../tests/Helpers/'.$className.'Test.php';
    }

    protected function getStubForInterface()
    {
        return __DIR__.'/stubs/helper-interface.stub';
    }

    protected function getStubForHelper()
    {
        return __DIR__.'/stubs/helper.stub';
    }

    protected function getStubForFacade()
    {
        return __DIR__.'/stubs/facade.stub';
    }

    protected function getStubForUnitTest()
    {
        return __DIR__.'/stubs/helper-unittest.stub';
    }

    protected function getBindServiceProviderPath()
    {
        return $this->laravel['path'].'/Providers/HelperBindServiceProvider.php';
    }

    protected function getAppConfigPath()
    {
        return $this->laravel['path'].'/../config/app.php';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Helpers';
    }
}
