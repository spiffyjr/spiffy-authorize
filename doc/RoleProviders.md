# SpiffyAuthorize Role Providers

Role providers tell the AuthorizeService how to load roles into its container. All role providers are listeners
that hook into various events to load roles depending on the application life-cycle.

## Config\RbacProvider

The Config\RbacProvider takes a simple array and prepares the Rbac container using the array. A sample configuration
would look like:

```php
return array(
    'spiffy_authorize' => array(
        'role_providers' => array(
            array(
                'name' => 'SpiffyAuthorize\Provider\Role\Config\RbacProvider',
                'options' => array(
                    'rules' => array(
                        'parent1' => array('child1','child2','child3'),
                        'child2'  => array('subchild1')
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
        'role_providers' => array(
            array(
                'name' => 'SpiffyAuthorize\Provider\Role\ObjectManager\RbacProvider',
                'options' => array(
                    'object_manager' => 'Doctrine\ORM\EntityManager', // service manager name of object manager instance
                    'target_class' => 'SpiffyCms\Entity\Role', // role entity class
                )
            )
        )
    )
);
```
