<?php

namespace App\Console\Commands\Generators;

class ServiceMakeCommand extends GeneratorCommandBase
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Service';

    protected function generate($name)
    {
        if (!$this->generateInterface($name)) {
            return false;
        }
        if (!$this->generateService($name)) {
            return false;
        }
        if (!$this->generateUnitTest($name)) {
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
        $interfacePath = $this->getInterfacePath($name);
        if ($this->alreadyExists($interfacePath)) {
            $this->error($name.' interface already exists.');

            return false;
        }

        $this->makeDirectory($interfacePath);

        $className = $this->getClassName($name);

        $interfaceStub = $this->files->get($this->getStubForInterface());
        $this->replaceTemplateVariable($interfaceStub, 'CLASS', $className);
        $this->files->put($interfacePath, $interfaceStub);

        return true;
    }

    protected function generateService($name)
    {
        $path = $this->getPath($name);
        if ($this->alreadyExists($path)) {
            $this->error($name.' already exists.');

            return false;
        }

        $this->makeDirectory($path);
        $className = $this->getClassName($name);

        $stub = $this->files->get($this->getStub());
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
        $bind = '$this->app->singleton('.PHP_EOL.'            \\App\\Services\\'.$className.'Interface::class,'.PHP_EOL.'            \\App\\Services\\Production\\'.$className.'::class'.PHP_EOL.'        );'.PHP_EOL.PHP_EOL.'        '.$key;
        $bindService = str_replace($key, $bind, $bindService);
        $this->files->put($this->getBindServiceProviderPath(), $bindService);

        return true;
    }

    protected function getInterfacePath($name)
    {
        $className = $this->getClassName($name);

        return $this->laravel['path'].'/Services/'.$className.'Interface.php';
    }

    protected function getPath($name)
    {
        $className = $this->getClassName($name);

        return $this->laravel['path'].'/Services/Production/'.$className.'.php';
    }

    protected function getStub()
    {
        return __DIR__.'/stubs/service.stub';
    }

    protected function getStubForInterface()
    {
        return __DIR__.'/stubs/service-interface.stub';
    }

    protected function getUnitTestPath($name)
    {
        $className = $this->getClassName($name);

        return $this->laravel['path'].'/../tests/Services/'.$className.'Test.php';
    }

    protected function getStubForUnitTest()
    {
        return __DIR__.'/stubs/service-unittest.stub';
    }

    protected function getTableName($name)
    {
        $className = $this->getClassName($name);

        return $className;
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
        return $rootNamespace.'\Services';
    }

    protected function getBindServiceProviderPath()
    {
        return $this->laravel['path'].'/Providers/ServiceBindServiceProvider.php';
    }
}
