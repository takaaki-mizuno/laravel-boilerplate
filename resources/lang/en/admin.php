<?php

return [
    'menu'   => [
        'dashboard'                 => 'Dashboard',
        'admin_users'               => 'Admin Users',
        'users'                     => 'Users',
        'site-configuration'        => 'Site Configuration',
    ],
    'errors' => [
        'general' => [
            'save_failed' => 'Failed To Save. Please contact with developers',
        ],
    ],
    'pages'  => [
        'common'                    => [
            'buttons' => [
                'create'  => 'Create New',
                'edit'    => 'Edit',
                'save'    => 'Save',
                'delete'  => 'Delete',
                'cancel'  => 'Cancel',
                'add'     => 'Add',
                'preview' => 'Preview',
            ],
        ],
        'auth'                      => [
            'buttons'  => [
                'sign_in' => 'Sign In',
            ],
            'messages' => [
                'remember_me'     => 'Remember Me',
                'please_sign_in'  => 'Sign in to start your session',
                'forgot_password' => 'I forgot my password',

            ],
        ],
    ],
    'roles'  => [
        'super_user'   => 'Super User',
        'site_admin'   => 'Site Administrator',
    ],
];