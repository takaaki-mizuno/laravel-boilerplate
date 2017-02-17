<?php

namespace App\Console\Commands\Generators;

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
    protected $description = 'Create a new model class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    protected function generate($name)
    {
        $this->generateModel($name);
        $this->generatePresenter($name);
        $this->addModelFactory($name);
        $this->generateUnitTest($name);
    }

    /**
     * @param  string $name
     * @return bool
     */
    protected function generateModel($name)
    {
        $path = $this->getPath($name);
        if ($this->alreadyExists($path)) {
            $this->error($name.' already exists.');

            return false;
        }

        $this->makeDirectory($path);
        $className = $this->getClassName($name);
        $tableName = $this->getTableName($name);

        $stub = $this->files->get($this->getStub());
        $this->replaceTemplateVariable($stub, 'CLASS', $className);
        $this->replaceTemplateVariable($stub, 'TABLE', $tableName);

        $columns = $this->getFillableColumns($tableName);
        $fillables = count($columns) > 0 ? "'".implode("',".PHP_EOL."        '", $columns)."'," : '';
        $this->replaceTemplateVariable($stub, 'FILLABLES', $fillables);

        $api = count($columns) > 0 ? implode(','.PHP_EOL.'            ', array_map(function ($column) {
                return "'".$column."'".' => $this->'.$column;
            }, $columns)).',' : '';
        $this->replaceTemplateVariable($stub, 'API', $api);

        $columns = $this->getDateTimeColumns($tableName);
        $datetimes = count($columns) > 0 ? "'".implode("','", $columns)."'" : '';
        $this->replaceTemplateVariable($stub, 'DATETIMES', $datetimes);

        $relations = $this->detectRelations($name);
        $this->replaceTemplateVariable($stub, 'RELATIONS', $relations);

        $hasSoftDelete = $this->hasSoftDeleteColumn($tableName);
        $this->replaceTemplateVariable($stub, 'SOFT_DELETE_CLASS_USE',
            $hasSoftDelete ? 'use Illuminate\Database\Eloquent\SoftDeletes;'.PHP_EOL : PHP_EOL);
        $this->replaceTemplateVariable($stub, 'SOFT_DELETE_USE', $hasSoftDelete ? 'use SoftDeletes;'.PHP_EOL : PHP_EOL);

        $this->files->put($path, $stub);

        return true;
    }

    /**
     * @param  string $name
     * @return string
     */
    protected function getPath($name)
    {
        $className = $this->getClassName($name);

        return $this->laravel['path'].'/Models/'.$className.'.php';
    }

    /**
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/model.stub';
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
        return $rootNamespace.'\Models';
    }

    /**
     * @param string $className
     *
     * @return string
     */
    protected function getModel($className)
    {
        return $className;
    }

    /**
     * @param  string $tableName
     * @return array
     */
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

    /**
     * @param  string $tableName
     * @return array
     */
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

    /**
     * @param  string $tableName
     * @return bool
     */
    protected function hasSoftDeleteColumn($tableName)
    {
        $columns = $this->getTableColumns($tableName, false);
        if ($columns) {
            foreach ($columns as $column) {
                $columnName = $column->getName();
                if ($columnName == 'deleted_at') {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param  string $name
     * @return bool
     */
    protected function generatePresenter($name)
    {
        $className = $this->getClassName($name);
        $tableName = $this->getTableName($name);

        $path = $this->getPresenterPath($name);
        if ($this->alreadyExists($path)) {
            $this->error($path.' already exists.');

            return false;
        }

        $stub = $this->files->get($this->getStubForPresenter());

        $columns = $this->getFillableColumns($tableName);
        $multilingualKeys = [];
        foreach ($columns as $column) {
            if (preg_match('/^(.*)_en$/', $column, $matches)) {
                $multilingualKeys[] = $matches[1];
            }
        }
        $multilingualKeyString = count($multilingualKeys) > 0 ? "'".join("','",
                array_unique($multilingualKeys))."'" : '';
        $this->replaceTemplateVariable($stub, 'MULTILINGUAL_COLUMNS', $multilingualKeyString);

        $imageFields = [];
        foreach ($columns as $column) {
            if (preg_match('/^(.*_image)_id$/', $column, $matches)) {
                $imageFields[] = $matches[1];
            }
        }
        $imageFieldString = count($imageFields) > 0 ? "'".join("','", array_unique($imageFields))."'" : '';
        $this->replaceTemplateVariable($stub, 'IMAGE_COLUMNS', $imageFieldString);

        $this->replaceTemplateVariable($stub, 'CLASS', $className);

        $this->files->put($path, $stub);

        return true;
    }

    /**
     * @param  string $name
     * @return string
     */
    protected function getPresenterPath($name)
    {
        $className = $this->getClassName($name);

        return $this->laravel['path'].'/Presenters/'.$className.'Presenter.php';
    }

    /**
     * @return string
     */
    protected function getStubForPresenter()
    {
        return __DIR__.'/stubs/presenter.stub';
    }

    /**
     * @param  string $name
     * @return bool
     */
    protected function addModelFactory($name)
    {
        $className = $this->getClassName($name);
        $tableName = $this->getTableName($name);

        $columns = $this->getTableColumns($tableName);

        $factory = $this->files->get($this->getFactoryPath());
        $key = '/* NEW MODEL FACTORY */';

        $data = '$factory->define(App\Models\\'.$className.'::class, function (Faker\Generator $faker) {'.PHP_EOL.'    return ['.PHP_EOL;
        foreach ($columns as $column) {
            if( preg_match('/_id$/', $column->getName()) ) {
                $defaultValue = 0;
            }else {
                $defaultValue = "''";
            }
            $data .= "        '".$column->getName()."' => ".$defaultValue.",".PHP_EOL;
        }
        $data .= '    ];'.PHP_EOL.'});'.PHP_EOL.PHP_EOL.$key;

        $factory = str_replace($key, $data, $factory);
        $this->files->put($this->getFactoryPath(), $factory);

        return true;
    }

    /**
     * @return string
     */
    protected function getFactoryPath()
    {
        return $this->laravel['path'].'/../database/factories/ModelFactory.php';
    }

    /**
     * @param  string $name
     * @return bool
     */
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

        $this->replaceTemplateVariable($stub, 'CLASS', $className);
        $this->replaceTemplateVariable($stub, 'class', strtolower(substr($className, 0, 1)).substr($className, 1));

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

        return $this->laravel['path'].'/../tests/Models/'.$className.'Test.php';
    }

    /**
     * @return string
     */
    protected function getStubForUnitTest()
    {
        return __DIR__.'/stubs/model-unittest.stub';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['table', '-t', InputOption::VALUE_OPTIONAL, 'Table Name', null],
        ];
    }

    protected function detectRelations($name)
    {
        $tableName = $this->getTableName($name);
        $columns = $this->getTableColumns($tableName);

        $relations = "";

        foreach ($columns as $column) {
            $columnName = $column->getName();
            if (preg_match('/^(.*_image)_id$/', $columnName, $matches)) {
                $relationName = \StringHelper::snake2Camel($matches[1]);
                $relations .= '    public function '.$relationName.'()'.PHP_EOL.'    {'.PHP_EOL.'        return $this->hasOne(\App\Models\Image::class, \'id\', \''.$columnName.'\');'.PHP_EOL.'    }'.PHP_EOL.PHP_EOL;
            } elseif (preg_match('/^(.*)_id$/', $columnName, $matches)) {
                $relationName = \StringHelper::snake2Camel($matches[1]);
                $className = ucfirst($relationName);
                if (!$this->getPath($className)) {
                    continue;
                }
                $relations .= '    public function '.$relationName.'()'.PHP_EOL.'    {'.PHP_EOL.'        return $this->belongsTo(\App\Models\\'.$className.'::class, \''.$columnName.'\', \'id\');'.PHP_EOL.'    }'.PHP_EOL.PHP_EOL;
            }
        }

        return $relations;
    }

    /**
     * @param  string $name
     * @return string
     */
    protected function getTableName($name)
    {
        $options = $this->option();
        if (array_key_exists('name', $options)) {
            return $optionName = $this->option('name');
        }

        $className = $this->getClassName($name);

        $name = \StringHelper::pluralize(\StringHelper::camel2Snake($className));
        $columns = $this->getTableColumns($name);
        if (count($columns)) {
            return $name;
        }

        $name = \StringHelper::singularize(\StringHelper::camel2Snake($className));
        $columns = $this->getTableColumns($name);
        if (count($columns)) {
            return $name;
        }

        return \StringHelper::pluralize(\StringHelper::camel2Snake($className));
    }

    /**
     * @param string $tableName
     * @param bool   $removeDefaultColumn
     *
     * @return \Doctrine\DBAL\Schema\Column[]
     */
    protected function getTableColumns($tableName, $removeDefaultColumn = true)
    {
        $hasDoctrine = interface_exists('Doctrine\DBAL\Driver');
        if (!$hasDoctrine) {
            return [];
        }

        $platform = \DB::getDoctrineConnection()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('json', 'string');

        $schema = \DB::getDoctrineSchemaManager();

        $columns = $schema->listTableColumns($tableName);

        if (!$removeDefaultColumn) {
            return $columns;
        }

        $ret = [];
        foreach ($columns as $column) {
            if (!in_array($column->getName(), ['created_at', 'updated_at', 'deleted_at'])) {
                $ret[] = $column;
            }
        }

        return $ret;
    }
}
