# SpiffyAuthorize Role Providers

Permission providers tell the RbacService how to load permissions into its container. All permission providers are
listeners that hook into various events to load permissions depending on the application life-cycle. It's important
to note that permissions are used with Rbac only!

## Config\RbacProvider

The Config\RbacProvider takes a simple array and prepares the Rbac container using the array. A sample configuration
would look like:

```php
return array(
    'spiffy_authorize' => array(
        'permission_providers' => array(
            array(
                'name' => 'SpiffyAuthorize\Provider\Permission\Config\RbacProvider',
                'options' => array(
                    'rules' => array(
                        'permission1' => array('role1', 'role2', 'role3')
                    )
                )
            )
        )
    )
);
```

## ObjectManager\RbacProvider

The ObjectManager\RbacProvider takes an object manager and prepares the Rbac container from it. A sample
configuration would look like:

```php
return array(
    'spiffy_authorize' => array(
        'permission_providers' => array(
            array(
                'name' => 'SpiffyAuthorize\Provider\Permission\ObjectManager\RbacProvider',
                'options' => array(
                    'object_manager' => 'Doctrine\ORM\EntityManager', // service manager name of object manager instance
                    'target_class' => 'SpiffyCms\Entity\Permission', // permission entity class
                )
            )
        )
    )
);
```