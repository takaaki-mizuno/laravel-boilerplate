<?php namespace App\Console\Commands\Generators;

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
        $tableName = $this->getTableName($name);

        $stub = $this->files->get($this->getStub($name));
        $this->replaceTemplateVariable($stub, 'CLASS', $className);
        $this->replaceTemplateVariable($stub, 'TABLE', $tableName);

        $columns = $this->getFillableColumns($tableName);
        $fillables = count($columns) > 0 ? "'" . join("'," . PHP_EOL . "        '", $columns) . "'," : '';
        $this->replaceTemplateVariable($stub, 'FILLABLES', $fillables);

        $api = count($columns) > 0 ? join(',' . PHP_EOL . '            ', array_map(function ($column) {
                return "'" . $column . "'" . ' => $this->' . $column;
            }, $columns)) . ',' : '';
        $this->replaceTemplateVariable($stub, 'API', $api);

        $columns = $this->getDateTimeColumns($tableName);
        $datetimes = count($columns) > 0 ? "'" . join("','", $columns) . "'" : '';
        $this->replaceTemplateVariable($stub, 'DATETIMES', $datetimes);

        $hasSoftDelete = $this->hasSoftDeleteColumn($tableName);
        $this->replaceTemplateVariable($stub, 'SOFT_DELETE_CLASS_USE',
            $hasSoftDelete ? 'use Illuminate\Database\Eloquent\SoftDeletes;' . PHP_EOL : PHP_EOL);
        $this->replaceTemplateVariable($stub, 'SOFT_DELETE_USE',
            $hasSoftDelete ? 'use SoftDeletes;' . PHP_EOL : PHP_EOL);

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
        return __DIR__ . '/stubs/model.stub';
    }

    protected function getTableName($name)
    {
        $className = $this->getClassName($name);
        $inflector = Inflector::get('en');

        return \StringHelper::pluralize(\StringHelper::camel2Snake($className));
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
     * @param  string           $className
     * @return \App\Models\Base
     */
    protected function getModel($className)
    {
        return $className;
    }

    protected function getFillableColumns($tableName)
    {
        $hasDoctrine = interface_exists('Doctrine\DBAL\Driver');
        if (!$hasDoctrine) {
            return [];
        }
        $ret = [];
        $schema = \DB::getDoctrineSchemaManager();
        $columns = $schema->listTableColumns($tableName);
        if ($columns) {
            foreach ($columns as $column) {
                if ($column->getAutoincrement()) {
                    continue;
                }
                $columnName = $column->getName();
                if (!in_array($columnName, ['created_at', 'updated_at', 'deleted_at'])) {
                    $ret[] = $columnName;
                }
            }
        }

        return $ret;
    }

    protected function getDateTimeColumns($tableName)
    {
        $hasDoctrine = interface_exists('Doctrine\DBAL\Driver');
        if (!$hasDoctrine) {
            return [];
        }
        $ret = [];
        $schema = \DB::getDoctrineSchemaManager();
        $columns = $schema->listTableColumns($tableName);
        if ($columns) {
            foreach ($columns as $column) {
                if ($column->getType() != 'DateTime') {
                    continue;
                }
                $columnName = $column->getName();
                if (!in_array($columnName, ['created_at', 'updated_at'])) {
                    $ret[] = $columnName;
                }
            }
        }

        return $ret;
    }

    protected function hasSoftDeleteColumn($tableName)
    {
        $hasDoctrine = interface_exists('Doctrine\DBAL\Driver');
        if (!$hasDoctrine) {
            return false;
        }
        $schema = \DB::getDoctrineSchemaManager();
        $columns = $schema->listTableColumns($tableName);
        if ($columns) {
            foreach ($columns as $column) {
                $columnName = $column->getName();
                if (in_array($columnName, ['deleted_at'])) {
                    return true;
                }
            }
        }

        return false;
    }

}
