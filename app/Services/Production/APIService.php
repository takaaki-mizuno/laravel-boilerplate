<?php namespace App\Services\Production;

use App\Services\APIServiceInterface;

class APIService extends BaseService implements APIServiceInterface
{

    const ERROR_UNKNOWN = 'unknown';
    const ERROR_NOT_FOUND = 'not_found';

    public function error($type)
    {
        $config = \Config::get('api.errors');
        $error = array_get($config, $type, $config[ $this::ERROR_UNKNOWN ]);

        return response()->json([
            'code'    => array_get($error, 'code', 100),
            'message' => array_get($error, 'message', ''),
        ], array_get($error, 'status_code', 400));
    }

    public function getAPIArray($models)
    {
        $result = [];
        foreach ($models as $model) {
            $return[] = $model->toAPIArray();
        }

        return $result;
    }

}
