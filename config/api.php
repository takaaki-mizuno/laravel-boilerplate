<?php

return [
    "errors" => [
        'unknown'   => [
            'code'        => 1000,
            'message'     => 'Unknown Error',
            'status_code' => 400,
        ],
        'not_found' => [
            'code'        => 1001,
            'message'     => 'Not Found',
            'status_code' => 400,
        ],
    ],
    "headers" => [
        'locale' => 'X-MYAPP-LOCALE',
        'session' => 'X-MYAPP-SESSION',
    ],
];