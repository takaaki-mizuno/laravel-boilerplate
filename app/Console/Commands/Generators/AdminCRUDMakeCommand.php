<?php

namespace App\Console\Commands\Generators;

class AdminCRUDMakeCommand extends GeneratorCommandBase
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:admin-crud';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Admin CRUD related files';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'AdminCRUD';

    protected function generate($name)
    {
        $modelName = $this->getModelName($name);

        if (!$this->generateController($modelName)) {
            return false;
        }

        if (!$this->generateRequest($modelName)) {
            return false;
        }
        if (!$this->generateViews($modelName)) {
            return false;
        }

        if (!$this->generateUnitTest($modelName)) {
        }

        if (!$this->addItemToSubMenu($modelName)) {
        }
        $this->generateLanguageFile($modelName);

        return $this->addRoute($modelName);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function generateController($name)
    {
        $path = $this->getControllerPath($name);
        if ($this->alreadyExists($path)) {
            $this->error($path.' already exists.');

            return false;
        }

        $this->makeDirectory($path);

        $stub = $this->files->get($this->getStubForController());
        $this->replaceTemplateVariables($stub, $name);
        $this->files->put($path, $stub);

        return true;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getModelName($name)
    {
        if (preg_match('/([A-Za-z0-9]+)(Controller)?$/', $name, $matches)) {
            $name = $matches[1];
        }

        return \StringHelper::singularize(ucwords($name));
    }

    protected function getColumns($name)
    {
        $modelFullName = '\\App\\Models\\'.$name;
        /** @var \App\Models\Base $model */
        $model = new $modelFullName();

        return $model->getFillableColumns();
    }

    /**
     * @param string $stub
     * @param string $modelName
     */
    protected function replaceTemplateVariables(&$stub, $modelName)
    {
        $this->replaceTemplateVariable($stub, 'CLASS', $modelName);
        $this->replaceTemplateVariable($stub, 'CLASSES', \StringHelper::pluralize($modelName));
        $this->replaceTemplateVariable($stub, 'class', strtolower(substr($modelName, 0, 1)).substr($modelName, 1));
        $this->replaceTemplateVariable($stub, 'classes', \StringHelper::pluralize(strtolower($modelName)));
        $this->replaceTemplateVariable($stub, 'classes-spinal',
            \StringHelper::camel2Spinal(\StringHelper::pluralize($modelName)));
        $this->replaceTemplateVariable($stub, 'classes-snake',
            \StringHelper::camel2Snake(\StringHelper::pluralize($modelName)));

        $columns = $this->getColumnNamesAndTypes($modelName);
        $columnNames = $this->getColumns($modelName);
        $params = [];
        $updates = '';
        foreach ($columns as $column) {
            if (!in_array($column['name'], $columnNames)) {
                continue;
            }
            if ($column['name'] == 'id' || $column['name'] == 'is_enabled') {
                continue;
            }
            if (\StringHelper::endsWith($column['name'], '_id')) {
                continue;
            }
            switch ($column['type']) {
                case 'BooleanType':
                    $updates .= '        $input[\''.$column['name'].'\'] = $request->get(\''.$column['name'].'\', 0);'.PHP_EOL;
                    break;
                case 'DateTimeType':
                case 'TextType':
                case 'StringType':
                case 'IntegerType':
                default:
                    $params[] = $column['name'];
            }
        }

        $list = implode(',', array_map(function ($name) {
            return "'".$name."'";
        }, $params));

        $this->replaceTemplateVariable($stub, 'COLUMNS', $list);
        $this->replaceTemplateVariable($stub, 'COLUMNUPDATES', $updates);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getControllerPath($name)
    {
        return $this->laravel['path'].'/Http/Controllers/Admin/'.$name.'Controller.php';
    }

    /**
     * @return string
     */
    protected function getStubForController()
    {
        return __DIR__.'/stubs/admin-crud-controller.stub';
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function generateRequest($name)
    {
        $path = $this->getRequestPath($name);
        if ($this->alreadyExists($path)) {
            $this->error($path.' already exists.');

            return false;
        }

        $this->makeDirectory($path);

        $stub = $this->files->get($this->getStubForRequest());
        $this->replaceTemplateVariables($stub, $name);
        $this->files->put($path, $stub);

        return true;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getRequestPath($name)
    {
        return $this->laravel['path'].'/Http/Requests/Admin/'.$name.'Request.php';
    }

    /**
     * @return string
     */
    protected function getStubForRequest()
    {
        return __DIR__.'/stubs/admin-crud-request.stub';
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function generateViews($name)
    {
        foreach (['index', 'edit'] as $type) {
            $path = $this->getViewPath($name, $type);
            if ($this->alreadyExists($path)) {
                $this->error($path.' already exists.');

                return false;
            }

            $this->makeDirectory($path);

            $stub = $this->files->get($this->getStubForView($type));
            if ($type == 'index') {
                $tableHeader = '';
                $tableContent = '';
                $columns = $this->getColumnNamesAndTypes($name);
                foreach ($columns as $column) {
                    if ($column['name'] == 'id' || $column['name'] == 'is_enabled') {
                        continue;
                    }
                    if (\StringHelper::endsWith($column['name'], '_id')) {
                        continue;
                    }
                    if ($column['type'] == 'TextType') {
                        continue;
                    }
                    $tableHeader .= '                <th>@lang(\'admin.pages.%%classes-spinal%%.columns.'.$column['name'].'\')</th>'.PHP_EOL;
                    $tableContent .= '                <td>{{ $model->'.$column['name'].' }}</td>'.PHP_EOL;
                }
                $this->replaceTemplateVariable($stub, 'TABLE_HEADER', $tableHeader);
                $this->replaceTemplateVariable($stub, 'TABLE_CONTENT', $tableContent);
            } elseif ($type == 'edit') {
                $inputs = $this->generateForm($name);
                $this->replaceTemplateVariable($stub, 'FORM', $inputs);

                $columns = $this->getColumnNamesAndTypes($name);
                $imageScript = '';
                foreach ($columns as $column) {
                    if (\StringHelper::endsWith($column['name'], 'image_id')) {
                        $fieldName = substr($column['name'], 0, strlen($column['name']) - 9);
                        $imageScript =        '$("#' . $fieldName . '-image").change(function (event) {'
                            .PHP_EOL. '                $("#' . $fieldName . '-image-preview").attr("src", URL.createObjectURL(event.target.files[0]));'
                            .PHP_EOL. '            });';
                    }
                }
                $this->replaceTemplateVariable($stub, 'IMAGE_SCRIPT', $imageScript);
            }

            $this->replaceTemplateVariables($stub, $name);
            $this->files->put($path, $stub);
        }

        return true;
    }

    protected function addItemToSubMenu($name)
    {
        $sideMenu = $this->files->get($this->getSideBarViewPath());

        $value = '<li @if( $menu==\''.\StringHelper::camel2Snake($name).'\') class="active" @endif ><a href="{!! action(\'Admin\\'.$name.'Controller@index\') !!}"><i class="fa fa-users"></i> <span>'.\StringHelper::pluralize($name).'</span></a></li>'.PHP_EOL.'            <!-- %%SIDEMENU%% -->';

        $sideMenu = str_replace('<!-- %%SIDEMENU%% -->', $value, $sideMenu);
        $this->files->put($this->getSideBarViewPath(), $sideMenu);
    }

    protected function getSideBarViewPath()
    {
        return $this->laravel['path'].'/../resources/views/layouts/admin/side_menu.blade.php';
    }

    /**
     * @param string $name
     * @param string $type
     *
     * @return string
     */
    protected function getViewPath($name, $type)
    {
        $directoryName = \StringHelper::camel2Spinal(\StringHelper::pluralize($name));

        return $this->laravel['path'].'/../resources/views/pages/admin/'.$directoryName.'/'.$type.'.blade.php';
    }

    /**
     * @param  string
     *
     * @return string
     */
    protected function getStubForView($type)
    {
        return __DIR__.'/stubs/admin-crud-view-'.$type.'.stub';
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function addRoute($name)
    {
        $directoryName = \StringHelper::camel2Spinal(\StringHelper::pluralize($name));

        $routes = $this->files->get($this->getRoutesPath());
        $key = '/* NEW ADMIN RESOURCE ROUTE */';
        $route = '\\Route::resource(\''.$directoryName.'\', \'Admin\\'.$name.'Controller\');'.PHP_EOL.'                '.$key;
        $routes = str_replace($key, $route, $routes);
        $this->files->put($this->getRoutesPath(), $routes);

        return true;
    }

    protected function getRoutesPath()
    {
        return $this->laravel['path'].'/../routes/admin.php';
    }

    protected function generateLanguageFile($name)
    {
        $directoryName = \StringHelper::camel2Spinal(\StringHelper::pluralize($name));

        $languages = $this->files->get($this->getLanguageFilePath());
        $key = '/* NEW PAGE STRINGS */';

        $columns = $this->getColumns($name);
        $data = "'".$directoryName."'   => [".PHP_EOL."            'columns'  => [".PHP_EOL;
        foreach ($columns as $column) {
            $data .= "                '".$column."' => '" . ucfirst($column) . "',".PHP_EOL;
        }
        $data .= '            ],'.PHP_EOL.'        ],'.PHP_EOL.'        '.$key;

        $languages = str_replace($key, $data, $languages);
        $this->files->put($this->getLanguageFilePath(), $languages);

        return true;
    }

    protected function generateForm($name)
    {
        $columns = $this->getColumnNamesAndTypes($name);
        $result = '';
        foreach ($columns as $column) {
            if ($column['name'] == 'id') {
                continue;
            }

            if (\StringHelper::endsWith($column['name'], 'image_id')) {
                $fieldName = substr($column['name'], 0, strlen($column['name']) - 3);
                $relationName = lcfirst(\StringHelper::snake2Camel($fieldName));
                $idName = \StringHelper::camel2Spinal($relationName);

                $template = '                    <div class="row">'
                    .PHP_EOL.'                        <div class="col-md-12">'
                    .PHP_EOL.'                            <div class="form-group text-center">'
                    .PHP_EOL.'                                @if( !empty($%%class%%->%%relation%%) )'
                    .PHP_EOL.'                                    <img id="%%id%%-preview"  style="max-width: 500px; width: 100%;" src="{!! $%%class%%->%%relation%%->getThumbnailUrl(480, 300) !!}" alt="" class="margin" />'
                    .PHP_EOL.'                                @else'
                    .PHP_EOL.'                                    <img id="%%id%%-preview" style="max-width: 500px; width: 100%;" src="{!! \URLHelper::asset(\'img/no_image.jpg\', \'common\') !!}" alt="" class="margin" />'
                    .PHP_EOL.'                                @endif'
                    .PHP_EOL.'                                <input type="file" style="display: none;"  id="%%id%%" name="%%field%%">'
                    .PHP_EOL.'                                <p class="help-block" style="font-weight: bolder;">'
                    .PHP_EOL.'                                    @lang(\'admin.pages.%%classes-spinal%%.columns.%%column%%\')'
                    .PHP_EOL.'                                    <label for="%%id%%" style="font-weight: 100; color: #549cca; margin-left: 10px; cursor: pointer;">@lang(\'admin.pages.common.buttons.edit\')</label>'
                    .PHP_EOL.'                                </p>'
                    .PHP_EOL.'                            </div>'
                    .PHP_EOL.'                        </div>'
                    .PHP_EOL.'                    </div>';
                $this->replaceTemplateVariable($template, 'column', $column['name']);
                $this->replaceTemplateVariable($template, 'field', $fieldName);
                $this->replaceTemplateVariable($template, 'relation', $relationName);
                $this->replaceTemplateVariable($template, 'id', $idName);
                $result = $result.PHP_EOL.$template.PHP_EOL;
                continue;
            }

            if (\StringHelper::endsWith($column['name'], '_id')) {
                continue;
            }

            switch ($column['type']) {
                case 'TextType':
                    $template =  '                    <div class="row">'
                        .PHP_EOL.'                        <div class="col-md-12">'
                        .PHP_EOL.'                            <div class="form-group @if ($errors->has(\'%%column%%\')) has-error @endif">'
                        .PHP_EOL.'                                <label for="%%column%%">@lang(\'admin.pages.%%classes-spinal%%.columns.%%column%%\')</label>'
                        .PHP_EOL.'                                <textarea name="%%column%%" class="form-control" rows="5" placeholder="@lang(\'admin.pages.%%classes-spinal%%.columns.%%column%%\')">{{ old(\'%%column%%\') ? old(\'%%column%%\') : $%%class%%->%%column%% }}</textarea>'
                        .PHP_EOL.'                            </div>'
                        .PHP_EOL.'                        </div>'
                        .PHP_EOL.'                    </div>';
                    $this->replaceTemplateVariable($template, 'column', $column['name']);
                    $this->replaceTemplateVariable($template, 'class', strtolower(substr($name, 0, 1)).substr($name, 1));
                    $this->replaceTemplateVariable($template, 'classes-spinal',
                        \StringHelper::camel2Spinal(\StringHelper::pluralize($name)));
                    $result = $result.PHP_EOL.$template.PHP_EOL;
                    break;
                case 'BooleanType':
                    $template =  '                    <div class="row">'
                        .PHP_EOL.'                        <div class="col-md-12">'
                        .PHP_EOL.'                            <div class="form-group">'
                        .PHP_EOL.'                                <div class="checkbox">'
                        .PHP_EOL.'                                    <label>'
                        .PHP_EOL.'                                        <input type="checkbox" name="%%column%%" value="1"'
                        .PHP_EOL.'                                        @if( $%%class%%->%%column%%) checked @endif >'
                        .PHP_EOL.'                                        @lang(\'admin.pages.%%classes-spinal%%.columns.%%column%%\')'
                        .PHP_EOL.'                                   </label>'
                        .PHP_EOL.'                                </div>'
                        .PHP_EOL.'                            </div>'
                        .PHP_EOL.'                        </div>'
                        .PHP_EOL.'                    </div>';
                    $this->replaceTemplateVariable($template, 'column', $column['name']);
                    $this->replaceTemplateVariable($template, 'class', strtolower(substr($name, 0, 1)).substr($name, 1));
                    $result = $result.PHP_EOL.$template.PHP_EOL;
                    break;
                case 'DateTimeType':
                    $template =  '                    <div class="row">'
                        .PHP_EOL.'                        <div class="col-md-12">'
                        .PHP_EOL.'                            <div class="form-group">'
                        .PHP_EOL.'                                <label for="%%column%%">@lang(\'admin.pages.%%classes-spinal%%.columns.%%column%%\')</label>'
                        .PHP_EOL.'                                <div class="input-group date datetime-field">'
                        .PHP_EOL.'                                    <input type="text" class="form-control" name="%%column%%"'
                        .PHP_EOL.'                                         value="{{ old(\'%%column%%\') ? old(\'%%column%%\') : $%%class%%->%%column%% }}">'
                        .PHP_EOL.'                                    <span class="input-group-addon">'
                        .PHP_EOL.'                                        <span class="glyphicon glyphicon-calendar"></span>'
                        .PHP_EOL.'                                    </span>'
                        .PHP_EOL.'                                </div>'
                        .PHP_EOL.'                            </div>'
                        .PHP_EOL.'                        </div>'
                        .PHP_EOL.'                    </div>';
                    $this->replaceTemplateVariable($template, 'column', $column['name']);
                    $this->replaceTemplateVariable($template, 'class', strtolower(substr($name, 0, 1)).substr($name, 1));
                    $result = $result.PHP_EOL.$template.PHP_EOL;
                    break;
                case 'StringType':
                case 'IntegerType':
                default:
                    $template =  '                    <div class="row">'
                        .PHP_EOL.'                        <div class="col-md-12">'
                        .PHP_EOL.'                            <div class="form-group @if ($errors->has(\'%%column%%\')) has-error @endif">'
                        .PHP_EOL.'                                <label for="%%column%%">@lang(\'admin.pages.%%classes-spinal%%.columns.%%column%%\')</label>'
                        .PHP_EOL.'                                <input type="text" class="form-control" id="%%column%%" name="%%column%%" value="{{ old(\'%%column%%\') ? old(\'%%column%%\') : $%%class%%->%%column%% }}">'
                        .PHP_EOL.'                            </div>'
                        .PHP_EOL.'                        </div>'
                        .PHP_EOL.'                    </div>';
                    $this->replaceTemplateVariable($template, 'column', $column['name']);
                    $this->replaceTemplateVariable($template, 'class', strtolower(substr($name, 0, 1)).substr($name, 1));
                    $this->replaceTemplateVariable($template, 'classes-spinal',
                        \StringHelper::camel2Spinal(\StringHelper::pluralize($name)));
                    $result = $result.PHP_EOL.$template.PHP_EOL;
            }
        }

        return $result;
    }

    protected function getLanguageFilePath()
    {
        return $this->laravel['path'].'/../resources/lang/en/admin.php';
    }

    protected function generateUnitTest($name)
    {
        $path = $this->getUnitTestPath($name);
        if ($this->alreadyExists($path)) {
            $this->error($path.' already exists.');

            return false;
        }

        $this->makeDirectory($path);

        $stub = $this->files->get($this->getStubForUnitTest());
        $this->replaceTemplateVariables($stub, $name);
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
        return $this->laravel['path'].'/../tests/Controllers/Admin/'.$name.'ControllerTest.php';
    }

    /**
     * @return string
     */
    protected function getStubForUnitTest()
    {
        return __DIR__.'/stubs/admin-crud-controller-unittest.stub';
    }

    protected function getColumnNamesAndTypes($name)
    {
        $columNames = $this->getColumns($name);
        $tableName = $this->getTableName($name);

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
                $columnType = array_slice(explode('\\', get_class($column->getType())), -1)[0];

                if (in_array($columnName, $columNames)) {
                    $ret[] = [
                        'name' => $columnName,
                        'type' => $columnType,
                    ];
                }
            }
        }

        return $ret;
    }

    protected function getTableName($name)
    {
        return \StringHelper::pluralize(\StringHelper::camel2Snake($name));
    }
}
