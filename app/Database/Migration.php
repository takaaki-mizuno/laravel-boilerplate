<?php

namespace App\Database;

class Migration extends \Illuminate\Database\Migrations\Migration
{
    public function getCurrentDatabaseDriver()
    {
        $connectionName = config('database.default');
        $currentDriver  = config('database.connections.'.$connectionName . '.driver', '');

        return $currentDriver;
    }

    public function updateTimestampDefaultValue($tableName, $onUpdate=[], $onCreate=[])
    {
        if ( $this->getCurrentDatabaseDriver() == 'mysql' ) {
            foreach ($onUpdate as $columnName) {
                \DB::statement('ALTER TABLE '.$tableName.' MODIFY `'.$columnName.'` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
            }

            foreach ($onCreate as $columnName) {
                \DB::statement('ALTER TABLE '.$tableName.' MODIFY `'.$columnName.'` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
            }
        }
    }

}
