<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\APIRequest;

class IndexController extends Controller
{
    public function test(APIRequest $request)
    {
        $data = $request->all();
        $paramsAllow = [
            'string'  => [
                'name',
                'telephone',
                'email',
                'address'
            ],
            'numeric' => [
                '>=0' => ['gender'],
                '<=1' => ['gender']
            ]
        ];
        $paramsRequire = [
            'name',
            'telephone',
            'email',
            'address'
        ];
        $validate = $request->checkParams($data, $paramsAllow, $paramsRequire);

        if ($validate['code'] != 100) {
            return $this->response($validate['code']);
        }
        $data = $validate['data'];

        return response()->json($data);
    }
}
