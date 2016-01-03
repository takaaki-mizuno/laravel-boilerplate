<?php namespace App\Console\Commands\Generators;

use Symfony\Component\Console\Input\InputOption;

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
        if (!$this->generateInterface($name)) {
            return false;
        }
        if (!$this->generateRepository($name)) {
            return false;
        }
        return $this->bindInterface($name);
    }

    /**
     * @param  string $name
     * @return bool
     */
    protected function generateInterface($name)
    {
        $interfacePath = $this->getInterfacePath($name);
        if ($this->alreadyExists($interfacePath)) {
            $this->error($name . ' interface already exists.');
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
     * @param  string $name
     * @return bool
     */
    protected function generateRepository($name)
    {
        $repositoryPath = $this->getRepositoryPath($name);
        if ($this->alreadyExists($repositoryPath)) {
            $this->error($name . ' already exists.');
            return false;
        }

        $this->makeDirectory($repositoryPath);


        $className = $this->getClassName($name);

        $repositoryStab = $this->files->get($this->getStubForRepository($name));
        $this->replaceTemplateVariable($repositoryStab, 'CLASS', $className);
        $this->replaceTemplateVariable($repositoryStab, 'MODEL', $this->getModel($className));
        $this->files->put($repositoryPath, $repositoryStab);

        return true;
    }

    /**
     * @param  string $name
     * @return bool
     */
    protected function bindInterface($name)
    {
        $bindService = $this->files->get($this->getBindServiceProviderPath());
        $key = '/* NEW BINDING */';
        $bind = '$this->app->singleton(' . PHP_EOL .
            "            'App\\Repositories\\" . $name . "Interface'," . PHP_EOL .
            "            'App\\Repositories\\Eloquent\\" . $name . "'" . PHP_EOL .
            "        );" . PHP_EOL . PHP_EOL . '        ' . $key;
        $bindService = str_replace($key, $bind, $bindService);
        $this->files->put($this->getBindServiceProviderPath(), $bindService);
        return true;
    }

    protected function getInterfacePath($name)
    {
        $className = $this->getClassName($name);
        return $this->laravel['path'] . '/Repositories/' . $className . 'Interface.php';
    }

    protected function getRepositoryPath($name)
    {
        $className = $this->getClassName($name);
        return $this->laravel['path'] . '/Repositories/Eloquent/' . $className . '.php';
    }

    protected function getStubForInterface($name)
    {
        $className = $this->getClassName($name);
        $model = $this->getModel($className);
        $instance = new $model();
        return is_array($instance->primaryKey) ? __DIR__ . '/stubs/composite-key-model-repository-interface.stub'
            : __DIR__ . '/stubs/single-key-model-repository-interface.stub';
    }

    protected function getStubForRepository($name)
    {
        $className = $this->getClassName($name);
        $model = $this->getModel($className);
        $instance = new $model();
        return is_array($instance->primaryKey) ? __DIR__ . '/stubs/composite-key-model-repository.stub'
            : __DIR__ . '/stubs/single-key-model-repository.stub';
    }

    protected function getBindServiceProviderPath()
    {
        return $this->laravel['path'] . '/Providers/RepositoryBindServiceProvider.php';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Repositories';
    }

    /**
     * @param  string $className
     * @return \App\Models\Base
     */
    protected function getModel($className)
    {
        $modelName = str_replace("Repository", "", $className);
        return $modelName;
    }

}
