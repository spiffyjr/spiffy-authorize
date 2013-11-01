# SpiffyAuthorize Authentication Provider

Identity providers tell SpiffyAuthorize about the current identity. By default, SpiffyAuthorize uses the
`SpiffyAuthorize\Provider\Identity\AuthenticationProvider` that inject the `Zend\Authentication\AuthenticationService` class
to get the logged user.

An identity provider can optionally return a guest role when the authentication provider does not have any
identity (by default: `guest`), or a default role if the identity returns 0 roles (by default: `member`).

## Registering the Zend\Authentication\AuthenticationService

The `Zend\Authentication\AuthenticationService` is automatically injected into the IdentityProvider by pulling it
from the service locator. This means you must have it defined in your application:

```php
return array(
    'service_manager' => array(
        'factories' => array(
            'Zend\Authentication\AuthenticationService' => function($sl) {
                // Do things
            }
        )
    )
);
```

## Customizing the default and guest roles

Here is how you can customize the default and guest role:

```php
return array(
    'spiffy_authorize' => array(
        'default_guest_role' => 'unknown',
        'default_role'       => 'foo_member'
    )
);
```