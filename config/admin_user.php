<?php

return [
    'roles' => [
        'super_user' => [
            'name' => 'admin.roles.super_user',
            'sub_roles' => ['site_admin', 'editor'],
        ],
        'site_admin' => [
            'name' => 'admin.roles.site_admin',
            'sub_roles' => ['editor'],
        ],
    ],
];
