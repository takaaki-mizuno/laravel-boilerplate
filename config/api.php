<?php

return [
    'errors' => [
        'unknown' => [
            'code' => 1000,
            'message' => 'Unknown Error',
            'status_code' => 400,
        ],
        'not_found' => [
            'code' => 1001,
            'message' => 'Not Found',
            'status_code' => 400,
        ],
        'auth_failed' => [
            'code' => 1002,
            'message' => 'Auth Failed',
            'status_code' => 401,
        ],
        'sign_in_required' => [
            'code' => 1003,
            'message' => 'Sign In Required',
            'status_code' => 401,
        ],
    ],
    'headers' => [
        'locale' => 'X-MYAPP-LOCALE',
        'session' => 'X-MYAPP-SESSION',
    ],
];
