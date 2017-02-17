<?php

namespace App\Console\Commands\Generators;

class CreateTableMigrationMakeCommand extends GeneratorCommandBase
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:create-migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new  migration for creating tabke';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    protected function generate($name)
    {
        $this->generateMigration($name);
    }

    protected function generateMigration($name)
    {
        $name = $this->getTableName($name);

        if (class_exists($className = $this->getClassName($name))) {
            throw new InvalidArgumentException("A $className migration already exists.");
        }

        $path = $this->getPath($name);

        $stub = $this->files->get($this->getStub());
        $this->replaceTemplateVariable($stub, 'CLASS', $className);
        $this->replaceTemplateVariable($stub, 'TABLE', $name);

        $this->files->put($path, $stub);

        return true;
    }

    protected function getTableName($name)
    {
        $name = str_replace('App\\', '', $name);

        return \StringHelper::pluralize(\StringHelper::camel2Snake($name));
    }

    protected function getClassName($name)
    {
        return 'Create'. ucfirst(\StringHelper::snake2Camel($name)).'Table';
    }

    protected function getPath($name)
    {
        $basePath = $this->laravel->databasePath().DIRECTORY_SEPARATOR.'migrations';

        return $basePath.'/'.date('Y_m_d_His').'_create_'.$name.'_table.php';
    }

    protected function getStub()
    {
        return __DIR__.'/stubs/create-table-migration.stub';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
}
