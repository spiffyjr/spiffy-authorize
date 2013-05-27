<?php

return [
    'factories' => [
        // Services that do not have an associated class.
        'SpiffyAuthorize\Guards'              => 'SpiffyAuthorize\Service\GuardFactory',
        'SpiffyAuthorize\PermissionProviders' => 'SpiffyAuthorize\Service\ProviderPermissionFactory',
        'SpiffyAuthorize\RoleProviders'       => 'SpiffyAuthorize\Service\ProviderRoleFactory',
        'SpiffyAuthorize\ViewStrategy'        => 'SpiffyAuthorize\Service\ViewStrategyFactory',

        // Services that map directly to a class.
        'SpiffyAuthorize\Guard\RouteGuard'                         => 'SpiffyAuthorize\Service\GuardRouteFactory',
        'SpiffyAuthorize\Guard\RouteParamsGuard'                   => 'SpiffyAuthorize\Service\GuardRouteParamsFactory',
        'SpiffyAuthorize\Provider\Identity\AuthenticationProvider' => 'SpiffyAuthorize\Service\ProviderIdentityFactory',
        'SpiffyAuthorize\Service\RbacService'                      => 'SpiffyAuthorize\Service\RbacServiceFactory',
        'SpiffyAuthorize\Options\ModuleOptions'                    => 'SpiffyAuthorize\Service\OptionsModuleFactory',
        'SpiffyAuthorize\View\Strategy\UnauthorizedStrategy'       => 'SpiffyAuthorize\Service\ViewStrategyUnauthorizedFactory',
    ]
];