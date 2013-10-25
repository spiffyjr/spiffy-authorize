# SpiffyAuthorize Guards

## What are guards?

Guards act as a layer of protection between the dispatch and response of your application. It's important to
note that guards *are not* a replacement for securing your service layer. It is *highly recommended* that your
service layer be protected as it should be the single point of entry into your business logic!

## When to use guards?

Guards are useful when you want to protect a lot of similar routes. For instance, you may have a lot of routes
that are child routes of a top "admin" route. You could "block" them all by using a simple route guard with a
regex rule ("admin*" in this case).

## Why NOT ONLY guards?

Guards may seems like a convenient way to handle authorization in your application. However, excepting in very
simple applications (or applications that have very very few authorization concerns), guards should not be used
alone.

Some services can be executed in various ways: either through a controller, through events… This means that a
user may access to a route in a legitimate way, but this route will call, at some points, a service that needs
finer-grained permissions.

That's why guards should not be used alone: your services should also be protected.

## Default guards

As of today, SpiffyAuthorize comes built-in with two guards: RouteGuard and RouteParamsGuard.

### RouteGuard

The route guard sits between the dispatch and onRoute events in the MVC application. This guard has a single
`rules` option that defines the routes and resources that apply to each route. A sample configuration would
look like:

```php
return array(
    'spiffy_authorize' => array(
        'guards' => array(
            array(
                'type'    => 'SpiffyAuthorize\Guard\RouteGuard',
                'options' => array(
                    'rules' => array(
                        'my_route' => array('resource1', 'resource2'),
                        'admin*'   => array('administrator')
                    )
                )
            )
        )
    )
);
```

Note: routes are matched via *regular expression* so you can do things like protect an entire endpoint (admin,
for example).

The configuration can be split in several modules, SpiffyAuthorize will automatically aggregate all guards.

Also note that you can avoid passing an array if you only have one permission. The previous rules can also be
rewritten the following way:

```php
'rules' => array(
	'my_route' => array('resource1', 'resource2'),
    'admin*'   => 'administrator'
)
```

### RouteParamsGuard

The route params guard is identical to the route guard except the resources information is pulled directly
from the route configuration. A sample configuration would look like:

```php
return array(
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type' => 'literal',
                'options' => array(
                    'route'    => '/admin',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Admin',
                        'action'     => 'index',
                        'resources'  => array(
                            'administrator' // access granted to administrator resource
                        )
                    ),
                ),
            ),
        ),
    ),
);
```
