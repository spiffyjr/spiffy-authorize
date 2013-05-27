<?php

return [
    'service_manager' => [
        'invokables' => [
            'Zend\Authentication\AuthenticationService' => 'Zend\Authentication\AuthenticationService'
        ]
    ],
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
                'options' => [
                    'rules' => [
                        'foo' => [
                            'bar'
                        ],
                    ]
                ]
            ],
            [
                'name' => 'SpiffyAuthorize\Guard\RouteGuard',
                'options' => [
                    'rules' => [
                        'baz' => [
                            'biz'
                        ]
                    ]
                ]
            ]
        ],
    ]
];