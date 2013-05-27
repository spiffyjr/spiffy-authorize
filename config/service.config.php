<?php

return [
    'factories' => [
        // Services that do not have an associated class.
        'SpiffyAuthorize\Guards'                => 'SpiffyAuthorize\Service\GuardFactory',
        'SpiffyAuthorize\PermissionProviders'   => 'SpiffyAuthorize\Service\ProviderPermissionFactory',
        'SpiffyAuthorize\RoleProviders'         => 'SpiffyAuthorize\Service\ProviderRoleFactory',

        // Services that map directly to a class.
        'SpiffyAuthorize\Guard\RouteGuard'      => 'SpiffyAuthorize\Service\GuardRouteGuardFactory',
        'SpiffyAuthorize\Service\RbacService'   => 'SpiffyAuthorize\Service\RbacServiceFactory',
        'SpiffyAuthorize\Options\ModuleOptions' => 'SpiffyAuthorize\Service\OptionsModuleOptionsFactory'
    ]
];