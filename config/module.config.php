<?php

return [
    'spiffy_authorize' => [
        // The service locator name used to get the authorize service.
        'authorize_service' => 'SpiffyAuthorize\Service\RbacService',

        // The role given to identities that are authenticated but do not have any roles themselves.
        'default_role' => 'member',

        // The role given to guests.
        'default_guest_role' => 'guest',

        // The service locator name used to get the identity provider.
        'identity_provider' => 'SpiffyAuthorize\Provider\Identity\AuthenticationProvider',

        // Permission provider listeners to be attached to the AuthorizeService (rbac only).
        'permission_providers' => [

        ],

        // Role provider listeners to be attached to the AuthorizeService.
        'role_providers' => [

        ],

        // Guard listeners to be attached to the application event manager.
        'guards' => [
            'params_guard' => [
                'name' => 'SpiffyAuthorize\Guard\RouteParamsGuard'
            ]
        ],

        // The service locator name used to get strategy listener to handle permission errors.
        'view_strategy' => 'SpiffyAuthorize\View\Strategy\UnauthorizedStrategy',

        // The template to use for displaying unauthorized errors'
        'view_template' => 'error/403',
    ],

    'zenddevelopertools' => [
        'collectors' => [
            'spiffy_authorize_permission_collector' => 'SpiffyAuthorize\Collector\PermissionCollector',
            'spiffy_authorize_role_collector'       => 'SpiffyAuthorize\Collector\RoleCollector',
        ],

        'toolbar' => [
            'entries' => [
                'spiffy_authorize_permission_collector' => 'zend-developer-tools/toolbar/spiffy-authorize-permission',
                'spiffy_authorize_role_collector'       => 'zend-developer-tools/toolbar/spiffy-authorize-role',
            ]
        ]
    ],

    'view_manager' => [
        'template_map' => [
            'error/403'                                                => __DIR__ . '/../view/error/403.phtml',
            'zend-developer-tools/toolbar/spiffy-authorize-permission' => __DIR__ . '/../view/zend-developer-tools/toolbar/spiffy-authorize-permission.phtml',
            'zend-developer-tools/toolbar/spiffy-authorize-role'       => __DIR__ . '/../view/zend-developer-tools/toolbar/spiffy-authorize-role.phtml',
        ]
    ]
];