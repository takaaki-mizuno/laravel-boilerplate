<?php

namespace App\Console\Commands\Generators;

use Symfony\Component\Console\Input\InputArgument;

class AddRelationCommand extends GeneratorCommandBase
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'boilerplate:relations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Relations';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'relations';

    public function articles()
    {
        return $this->belongsToMany(\App\Models\Article::class, FeatureArticle::getTableName(), 'feature_id',
            'article_id')->orderBy('order', 'asc');
    }

    protected function generate($name)
    {
        $existingRelations = $this->getExistingRelations();
        $newRelations = $this->getNewRelations($existingRelations);
        $this->addRelations($newRelations);
    }

    protected function addRelations($relations)
    {
        foreach ($relations as $model => $list) {
            if (count($list) == 0) {
                continue;
            }
            $path = $this->getModelPath($model);
            $file = $this->files->get($path);

            $string = "// Relations".PHP_EOL.PHP_EOL;
            foreach ($list as $name => $relation) {
                $relationString = '    public function '.$name.'()'.PHP_EOL.'    {'.PHP_EOL;
                switch ($relation['type']) {
                    case "belongsTo":
                        $relationString .= '        return $this->belongsTo('.$relation['model'].'::class, \''.$relation['localKey'].'\', \''.$relation['foreignKey'].'\');'.PHP_EOL;
                        break;
                    case "hasMany":
                        $relationString .= '        return $this->hasMany('.$relation['model'].'::class, \''.$relation['localKey'].'\', \''.$relation['foreignKey'].'\');'.PHP_EOL;
                        break;
                    case "BelongsToMany":
                        $relationString .= '        return $this->belongsToMany('.$relation['model'].'::class, '.$relation['tableName'].',\''.$relation['foreignKey'].'\', \''.$relation['otherKey'].'\');'.PHP_EOL;
                        break;
                    case "hasOne":
                        $relationString .= '        return $this->hasOne('.$relation['model'].'::class, \''.$relation['localKey'].'\', \''.$relation['foreignKey'].'\');'.PHP_EOL;
                        break;
                }
                $relationString .= '    }'.PHP_EOL.PHP_EOL;

                $string .= $relationString;
            }
            $file = str_replace("// Relations", $string, $file);
//            $this->files->put($path, $file);
            print $string;
        }
    }

    protected function getExistingRelations()
    {
        $relations = [];
        $modelNames = $this->getModelNames();
        foreach ($modelNames as $modelName) {
            $relations[$modelName] = $this->getCurrentRelations($modelName);
        }

        return $relations;
    }

    protected function getNewRelations($existingRelations)
    {
        $newRelations = [];
        $modelNames = $this->getModelNames();

        foreach ($modelNames as $modelName) {
            $tableName = $this->getTableName($modelName);
            $columns = $this->getTableColumns($tableName);
            $newRelations[$modelName] = [];
            foreach ($columns as $column) {
                $columnName = $column->getName();
                if (preg_match('/^(.*_image)_id$/', $columnName, $matches)) {
                    $relationName = \StringHelper::snake2Camel($matches[1]);
                    if (!key_exists($relationName, $existingRelations[$modelName])) {
                        $newRelations[$modelName][$relationName] = [
                            'type'       => 'belongsTo',
                            'model'      => '\\App\\Models\\Image',
                            'localKey'   => $columnName,
                            'foreignKey' => 'id',
                        ];
                    }
                } elseif (preg_match('/^(.*)_id$/', $columnName, $matches)) {
                    $relationName = \StringHelper::snake2Camel($matches[1]);
                    $className = ucfirst($relationName);
                    if (!$this->files->exists($this->getPath($className))) {
                        continue;
                    }
                    if (!key_exists($relationName, $existingRelations[$modelName])) {
                        $newRelations[$modelName][$relationName] = [
                            'type'       => 'belongsTo',
                            'model'      => '\\App\\Models\\'.$className,
                            'localKey'   => $columnName,
                            'foreignKey' => 'id',
                        ];
                    }
                    $reverseRelationName = \StringHelper::snake2Camel($tableName);
                    if (!key_exists($reverseRelationName, $existingRelations[$className])) {
                        $newRelations[$className][$reverseRelationName] = [
                            'type'       => 'hasMany',
                            'model'      => '\\App\\Models\\'.$modelName,
                            'localKey'   => 'id',
                            'foreignKey' => $columnName,
                        ];
                    }
                }
            }
            // Check belongsToMany Relationship
            $relationNames = array_keys($existingRelations[$modelName]) + array_keys($newRelations[$modelName]);
            if (count($relationNames) === 2) {
                if (($modelName == ucfirst($relationNames[0]).ucfirst(\StringHelper::pluralize($relationNames[1]))) || ($modelName == ucfirst($relationNames[1]).ucfirst(\StringHelper::pluralize($relationNames[0])))) {
                    $parent = ucfirst($relationNames[0]);
                    $child = ucfirst($relationNames[1]);

                    $relationName = \StringHelper::pluralize($relationNames[0]);
                    if (!key_exists($relationName, $existingRelations[$child])) {
                        $newRelations[$modelName][$relationName] = [
                            'type'       => 'BelongsToMany',
                            'model'      => '\\App\\Models\\'.$child,
                            'table'      => '\\App\\Models\\'.$modelName.'::getTableName()',
                            'foreignKey' => \StringHelper::camel2Snake($child).'_id',
                            'otherKey'   => \StringHelper::camel2Snake($parent).'_id',
                        ];
                    }

                    $relationName = \StringHelper::pluralize($relationNames[1]);
                    if (!key_exists($relationName, $existingRelations[$parent])) {
                        $newRelations[$modelName][$relationName] = [
                            'type'       => 'BelongsToMany',
                            'model'      => '\\App\\Models\\'.$parent,
                            'table'      => '\\App\\Models\\'.$modelName.'::getTableName()',
                            'foreignKey' => \StringHelper::camel2Snake($parent).'_id',
                            'otherKey'   => \StringHelper::camel2Snake($child).'_id',
                        ];
                    }
                }
            }
        }

        return $newRelations;
    }

    protected function getModelNames()
    {
        $modelNames = [];
        foreach (scandir($this->getModelDirectory()) as $fileName) {
            if (preg_match('/([A-Za-z0-9]+)\.php$/', $fileName, $matches)) {
                $modelNames[] = $matches[1];
            }
        }

        return $modelNames;
    }

    /**
     * @param  string $name
     * @return string
     */
    protected function getModelPath($name)
    {
        $className = $this->getClassName($name);

        return $this->getModelDirectory().'/'.$className.'.php';
    }

    /**
     * @return string
     */
    protected function getModelDirectory()
    {
        return $this->laravel['path'].'/Models';
    }

    /**
     * @param  string $name
     * @return string
     */
    protected function getClassFullName($name)
    {
        return '\\App\\Models\\'.$name;
    }

    /**
     * @param  string $modelName
     * @return array
     */
    protected function getCurrentRelations($modelName)
    {
        $relations = [];
        $methods = get_class_methods($this->getClassFullName($modelName));
        foreach ($methods as $method) {
            $relation = $this->isRelationMethod($modelName, $method);
            if (!empty($relation)) {
                $relations[$method] = $relation;
                continue;
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

    protected function isRelationMethod($modelName, $methodName)
    {
        $path = $this->getModelPath($modelName);
        $modelFile = $this->files->get($path);

        if (preg_match('/function\\s+'.$methodName.'\\s*\(\)\\s+{([^}]+)}/m', $modelFile, $matches)) {
            $funcCode = $matches[1];
            if (preg_match('/(hasOne|hasMany|belongsTo|belongsToMany)/m', $funcCode, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::OPTIONAL, 'The name of the class'],
        ];
    }
}
