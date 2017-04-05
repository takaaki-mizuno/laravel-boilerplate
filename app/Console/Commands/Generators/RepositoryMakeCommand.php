<?php

namespace App\Console\Commands\Generators;

class RepositoryMakeCommand extends GeneratorCommandBase
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

    protected function generate($name)
    {
        $this->generateInterface($name);
        $this->generateRepository($name);
        $this->generateUnitTest($name);

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

        $interfaceStub = $this->files->get($this->getStubForInterface($name));
        $this->replaceTemplateVariable($interfaceStub, 'CLASS', $className);
        $this->replaceTemplateVariable($interfaceStub, 'MODEL', $this->getModel($className));
        $this->files->put($interfacePath, $interfaceStub);

        return true;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function generateRepository($name)
    {
        $repositoryPath = $this->getRepositoryPath($name);
        if ($this->alreadyExists($repositoryPath)) {
            $this->error($name.' already exists.');

            return false;
        }

        $this->makeDirectory($repositoryPath);

        $className = $this->getClassName($name);

        $repositoryStab = $this->files->get($this->getStubForRepository($name));
        $this->replaceTemplateVariable($repositoryStab, 'CLASS', $className);
        $this->replaceTemplateVariable($repositoryStab, 'MODEL',
            str_replace('\\App\\Models\\', '', $this->getModel($className)));
        $this->files->put($repositoryPath, $repositoryStab);

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
        $bind = '$this->app->singleton('.PHP_EOL."            \\App\\Repositories\\".$className."Interface::class,".PHP_EOL."            \\App\\Repositories\\Eloquent\\".$className."::class".PHP_EOL.'        );'.PHP_EOL.PHP_EOL.'        '.$key;
        $bindService = str_replace($key, $bind, $bindService);
        $this->files->put($this->getBindServiceProviderPath(), $bindService);

        return true;
    }

    protected function getInterfacePath($name)
    {
        $className = $this->getClassName($name);

        return $this->laravel['path'].'/Repositories/'.$className.'Interface.php';
    }

    protected function getRepositoryPath($name)
    {
        $className = $this->getClassName($name);

        return $this->laravel['path'].'/Repositories/Eloquent/'.$className.'.php';
    }

    protected function getStubForInterface($name)
    {
        $className = $this->getClassName($name);
        $model = $this->getModel($className);
        $instance = new $model();

        return is_array($instance->primaryKey) ? __DIR__.'/stubs/composite-key-model-repository-interface.stub' : __DIR__.'/stubs/single-key-model-repository-interface.stub';
    }

    protected function getStubForRepository($name)
    {
        $className = $this->getClassName($name);
        $model = $this->getModel($className);
        $instance = new $model();

        return is_array($instance->primaryKey) ? __DIR__.'/stubs/composite-key-model-repository.stub' : __DIR__.'/stubs/single-key-model-repository.stub';
    }

    protected function getBindServiceProviderPath()
    {
        return $this->laravel['path'].'/Providers/RepositoryBindServiceProvider.php';
    }

    protected function generateUnitTest($name)
    {
        $className = $this->getClassName($name);

        $path = $this->getUnitTestPath($name);
        if ($this->alreadyExists($path)) {
            $this->error($path.' already exists.');

            return false;
        }

        $this->makeDirectory($path);

        $stub = $this->files->get($this->getStubForUnitTest());

        $model = $this->getModelClass($className);

        $this->replaceTemplateVariable($stub, 'MODEL', $model);
        $this->replaceTemplateVariable($stub, 'model', strtolower(substr($model, 0, 1)).substr($model, 1));
        $this->replaceTemplateVariable($stub, 'models',
            \StringHelper::pluralize(strtolower(substr($model, 0, 1)).substr($model, 1)));

        $this->files->put($path, $stub);

        return true;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getUnitTestPath($name)
    {
        $className = $this->getClassName($name);

        return $this->laravel['path'].'/../tests/Repositories/'.$className.'Test.php';
    }

    /**
     * @return string
     */
    protected function getStubForUnitTest()
    {
        return __DIR__.'/stubs/single-key-model-repository-unittest.stub';
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
        return $rootNamespace.'\Repositories';
    }

    /**
     * @param string $className
     *
     * @return string
     */
    protected function getModel($className)
    {
        $modelName = str_replace('Repository', '', $className);

        return '\\App\\Models\\'.$modelName;
    }

    /**
     * @param string $className
     *
     * @return \App\Models\Base
     */
    protected function getModelClass($className)
    {
        $modelName = str_replace('Repository', '', $className);

        return $modelName;
    }
}
