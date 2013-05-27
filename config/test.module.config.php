<?php

return [
    'spiffy-authorize' => [
        'permission_providers' => [
            [
                'name'    => 'SpiffyAuthorize\Provider\Permission\Config\RbacProvider',
                'options' => [
                    'rules' => [
                        'foo' => [ 'bar', 'baz' ]
                    ]
                ]
            ]
        ],
        'role_providers' => [
            [
                'name'    => 'SpiffyAuthorize\Provider\Role\Config\RbacProvider',
                'options' => [
                    'rules' => [
                        'admin' => ['moderator']
                    ]
                ]
            ]
        ],
        'guards' => [
            [
                'name'  => 'SpiffyAuthorize\Guard\RouteGuard',
                'rules' => [
                    'foo' => [
                        'bar'
                    ],
                ]
            ],
            [
                'name' => 'SpiffyAuthorize\Guard\RouteGuard',
                'rules' => [
                    'baz' => [
                        'biz'
                    ]
                ]
            ]
        ],
    ]
];