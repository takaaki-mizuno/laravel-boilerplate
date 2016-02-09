<?php namespace App\Console\Commands\Generators;

use Symfony\Component\Console\Input\InputOption;

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
    protected $description = 'Create a new  service class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Service';

    protected function generate($name)
    {
        if (!$this->generateService($name)) {
            return false;
        }
        if (!$this->generateUnitTest($name)) {
            return false;
        }

        return true;
    }

    protected function generateService($name)
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
        $this->files->put($path, $stub);

        return true;
    }

    /**
     * @param  string $name
     * @return bool
     */
    protected function generateUnitTest($name)
    {
        $path = $this->getUnitTestPath($name);
        if ($this->alreadyExists($path)) {
            $this->error($name . ' already exists.');

            return false;
        }

        $this->makeDirectory($path);

        $className = $this->getClassName($name);

        $stub = $this->files->get($this->getStubForUnitTest($name));
        $this->replaceTemplateVariable($stub, 'CLASS', $className);
        $this->files->put($path, $stub);

        return true;
    }

    protected function getPath($name)
    {
        $className = $this->getClassName($name);

        return $this->laravel['path'] . '/Services/' . $className . '.php';
    }

    protected function getStub($name)
    {
        return __DIR__ . '/stubs/service.stub';
    }

    protected function getUnitTestPath($name)
    {
        $className = $this->getClassName($name);

        return $this->laravel['path'] . '/../tests/Services/' . $className . 'Test.php';
    }

    protected function getStubForUnitTest($name)
    {
        $className = $this->getClassName($name);

        return __DIR__ . '/stubs/service-unittest.stub';
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
        return $rootNamespace . '\Services';
    }

}
