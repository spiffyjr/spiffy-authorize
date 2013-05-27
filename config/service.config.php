<?php

return [
    'factories' => [
        // Services that do not have an associated class.
        'SpiffyAuthorize\Guards'                => 'SpiffyAuthorize\Service\GuardFactory',
        'SpiffyAuthorize\PermissionProviders'   => 'SpiffyAuthorize\Service\ProviderPermissionFactory',
        'SpiffyAuthorize\RoleProviders'         => 'SpiffyAuthorize\Service\ProviderRoleFactory',

        // Services that map directly to a class.
        'SpiffyAuthorize\RbacService'           => 'SpiffyAuthorize\Service\RbacServiceFactory',
        'SpiffyAuthorize\Options\ModuleOptions' => 'SpiffyAuthorize\Service\OptionsModuleOptionsFactory'
    ]
];