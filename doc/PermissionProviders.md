# SpiffyAuthorize Role Providers

Permission providers tell the RbacService how to load permissions into its container. All permission providers are
listeners that hook into various events to load permissions depending on the application life-cycle. It's important
to note that permissions are used with Rbac only!

## Config\RbacProvider

The Config\RbacProvider takes a simple array and prepares the Rbac container using the array. A sample configuration
would look like:

```php
return [
    'spiffy-authorize' => [
        'permission_providers' => [
            [
                'name' => 'SpiffyAuthorize\Provider\Permission\Config\RbacProvider',
                'options' => [
                    'rules' => [
                        'permission1' => ['role1', 'role2', 'role3']
                    ]
                ]
            ]
        ]
    ]
];
```

## ObjectRepository\RbacProvider

The ObjectRepository\RbacProvider takes an object repository and prepares the Rbac container from it. A sample
configuration would look like:

```php
return [
    'spiffy-authorize' => [
        'permission_providers' => [
            [
                'name' => 'SpiffyAuthorize\Provider\Permission\ObjectRepository\RbacProvider'
            ]
        ]
    ]
];
```

The configuration above will use the `SpiffyAuthorize\Service\ProviderPermissionRbacObjectRepositoryFactory` to create
the service using the default object repository `Doctrine\ORM\EntityManager`. If your object repository differs you will
need to overwrite the default factory with your own implementation.