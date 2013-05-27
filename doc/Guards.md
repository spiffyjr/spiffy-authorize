# SpiffyAuthorize Guards

Guards act as a layer of protection between the dispatch and response of your application. It's important to note
that guards *are not* a replacement for securing your service layer. It is *highly recommended* that your service
layer be protected as it should be the single point of entry into your business logic!

## RouteGuard

The route guard sits between the dispatch and onRoute events in the MVC application. This guard has a single `rules`
option that defines the routes and resources that apply to each route. A sample configuration would look like:

```php
return [
    'spiffy_authorize' => [
        'guards' => [
            [
                'name' => 'SpiffyAuthorize\Guard\RouteGuard',
                'options' => [
                    'rules' => [
                        'my_route' => ['resource1', 'resource2'],
                        'admin*'   => ['administrator']
                    ]
                ]
            ]
        ]
    ]
];
```

Note: routes are matched via *regular expression* so you can do things like protect an entire endpoint (admin, for example).

## RouteParamsGuard

The route params guard is identical to the route guard except the resources information is pulled directly from the
route configuration. A sample configuration would look like:

```php
return [
    'router' => [
        'routes' => [
            'admin' => [
                'type' => 'literal',
                'options' => [
                    'route'    => '/admin',
                    'defaults' => [
                        'controller' => 'Application\Controller\Admin',
                        'action'     => 'index',
                        'resources'  => [
                            'administrator' // access granted to administrator resource
                        ]
                    ],
                ],
            ],
        ],
    ],
];
```