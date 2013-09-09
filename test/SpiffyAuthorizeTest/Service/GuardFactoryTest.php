<?php

namespace SpiffyAuthorizeTest\Service;

use SpiffyAuthorize\ModuleOptions;
use SpiffyAuthorize\Service\GuardFactory;
use Zend\ServiceManager\ServiceManager;

class GuardFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGuardsCreated()
    {
        $config = array(
            array(
                'name'  => 'SpiffyAuthorize\Guard\RouteGuard',
                'options' => array(
                    'rules' => array(
                        'route' => array(
                            'perm1',
                            'perm2'
                        )
                    )
                )
            ),
            array(
                'name'  => 'SpiffyAuthorize\Guard\RouteGuard',
                'options' => array(
                    'rules' => array(
                        'route2' => array(
                            'perm3',
                            'perm4'
                        )
                    )
                )
            )
        );

        $options = new ModuleOptions();
        $options->setGuards($config);

        $sm = new ServiceManager();
        $sm->setService('SpiffyAuthorize\ModuleOptions', $options);

        $factory = new GuardFactory();
        $guards  = $factory->createService($sm);

        $this->assertCount(2, $guards);
        foreach ($guards as $guard) {
            $this->assertInstanceOf('SpiffyAuthorize\Guard\GuardInterface', $guard);
        }

        $rules1 = $guards[0]->getRules();
        $rules2 = $guards[1]->getRules();

        $this->assertCount(1, $rules1);
        $this->assertCount(1, $rules2);
        $this->assertEquals('perm1', $rules1['route'][0]);
        $this->assertEquals('perm4', $rules2['route2'][1]);
    }
}