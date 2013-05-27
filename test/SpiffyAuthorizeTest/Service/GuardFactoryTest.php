<?php

namespace SpiffyAuthorizeTest\Service;

use SpiffyAuthorize\Options\ModuleOptions;
use SpiffyAuthorize\Service\GuardFactory;
use Zend\ServiceManager\ServiceManager;

class GuardFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGuardsCreated()
    {
        $config = [
            [
                'name'  => 'SpiffyAuthorize\Guard\RouteGuard',
                'rules' => [
                    'route' => [
                        'perm1',
                        'perm2'
                    ]
                ]
            ],
            [
                'name'  => 'SpiffyAuthorize\Guard\RouteGuard',
                'rules' => [
                    'route2' => [
                        'perm3',
                        'perm4'
                    ]
                ]
            ]
        ];

        $options = new ModuleOptions();
        $options->setGuards($config);

        $sm = new ServiceManager();
        $sm->setService('SpiffyAuthorize\Options\ModuleOptions', $options);

        $factory = new GuardFactory();
        $guards  = $factory->createService($sm);

        $this->assertCount(2, $guards);
        foreach ($guards as $guard) {
            $this->assertInstanceOf('SpiffyAuthorize\Guard\GuardInterface', $guard);
        }

        $this->assertCount(1, $guards[0]->getRules());
        $this->assertCount(1, $guards[1]->getRules());
        $this->assertEquals('perm1', $guards[0]->getRules()['route'][0]);
        $this->assertEquals('perm4', $guards[1]->getRules()['route2'][1]);
    }
}