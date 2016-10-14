<?php

return [
    'webHookUrl' => env('SLACK_WEB_HOOK_URL'),
    'types' => [
        'serious-alert' => [
            'channel' => '#general',
            'username' => 'Alert Bot',
            'icon' => ':icon_bot_famarry_error:',
            'color' => 'bad',
        ],
    ],
    'default' => [
        'channel' => '#general',
        'username' => 'Bot',
        'icon' => ':smile:',
        'color' => 'good',
    ],
];
