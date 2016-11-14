<?php namespace App\Helpers\Production;

use App\Helpers\TypeHelperInterface;

class TypeHelper implements TypeHelperInterface
{

    public function getTypeName($type, $list)
    {
        $typeInfo = array_get($list, $type);
        if (empty($typeInfo)) {
            return "";
        }

        return trans(array_get($typeInfo, 'name'));
    }

    public function getTypeList($table, $key)
    {
        $ret = [];
        $types = config($table. '.' . \StringHelper::pluralize($key), []);
        foreach ($types as $type => $info) {
            $ret[$type] = trans($info['name']);
        }

        return $ret;
    }
}
