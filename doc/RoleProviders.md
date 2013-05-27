# SpiffyAuthorize Role Providers

Role providers tell the AuthorizeService how to load roles into its container. All role providers are listeners
that hook into various events to load roles depending on the application life-cycle.

## Config\RbacProvider

The Config\RbacProvider takes a simple array and prepares the Rbac container using the array. A sample configuration
would look like:

```php
return [
    'spiffy-authorize' => [
        'role_providers' => [
            [
                'name' => 'SpiffyAuthorize\Provider\Role\Config\RbacProvider',
                'options' => [
                    'rules' => [
                        'parent1' => ['child1,'child2','child3'],
                        'child2'  => ['subchild1']
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
        'role_providers' => [
            [
                'name' => 'SpiffyAuthorize\Provider\Role\ObjectRepository\RbacProvider'
            ]
        ]
    ]
];
```

The configuration above will use the `SpiffyAuthorize\Service\ProviderRoleRbacObjectRepositoryFactory` to create the
service using the default object repository `Doctrine\ORM\EntityManager`. If your object repository differs you will
need to overwrite the default factory with your own implementation.