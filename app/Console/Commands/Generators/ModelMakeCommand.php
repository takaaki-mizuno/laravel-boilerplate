<?php namespace App\Console\Commands\Generators;

use Symfony\Component\Console\Input\InputOption;

class ModelMakeCommand extends GeneratorCommandBase
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:new-model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new  model class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    protected function generate($name)
    {

        $path = $this->getPath($name);
        if ($this->alreadyExists($path)) {
            $this->error($name . ' already exists.');
            return false;
        }

        $this->makeDirectory($path);
        $className = $this->getClassName($name);

        $stub = $this->files->get($this->getStub($name));
        $this->replaceTemplateVariable($stub, 'CLASS', $className);
        $this->replaceTemplateVariable($stub, 'TABLE', $this->getTableName($className));
        $this->files->put($path, $stub);

        return true;
    }

    protected function getPath($name)
    {
        $className = $this->getClassName($name);
        return $this->laravel['path'] . '/Models/' . $className . '.php';
    }

    protected function getStub($name)
    {
        return  __DIR__ . '/stubs/model.stub';
    }

    protected function getTableName($name)
    {
        $className = $this->getClassName($name);
        return $className;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Models';
    }

    /**
     * @param  string $className
     * @return \App\Models\Base
     */
    protected function getModel($className)
    {
        return $className;
    }

}
