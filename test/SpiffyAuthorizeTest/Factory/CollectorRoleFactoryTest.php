<?php

namespace SpiffyAuthorizeTest\Factory;

use Mockery as m;
use SpiffyAuthorize\ModuleOptions;
use SpiffyAuthorize\Provider\Identity\AuthenticationProvider;
use SpiffyAuthorize\Factory\CollectorRoleFactory;
use Zend\ServiceManager\ServiceManager;

class CollectorRoleFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceReturned()
    {
        $options = new ModuleOptions();
        $options->setIdentityProvider('IdentityProvider');

        $sm = m::mock('Zend\ServiceManager\ServiceManager');
        $sm->shouldReceive('get')->with('IdentityProvider')->andReturn(new AuthenticationProvider());
        $sm->shouldReceive('get')->with('SpiffyAuthorize\ModuleOptions')->andReturn($options);

        $factory  = new CollectorRoleFactory();
        $instance = $factory->createService($sm);

        $this->assertInstanceOf('SpiffyAuthorize\Collector\RoleCollector', $instance);
    }
}
