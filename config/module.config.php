<?php

return [
    'spiffy-authorize' => [
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

        ],

        // The service locator name used to get strategy listener to handle permission errors.
        'view_strategy' => 'SpiffyAuthorize\View\Strategy\UnauthorizedStrategy',

        // The template to use for displaying unauthorized errors'
        'view_template' => 'error/403'
    ]
];