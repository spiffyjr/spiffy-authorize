<?php

namespace SpiffyAuthorizeTest\Factory;

use Mockery as m;
use SpiffyAuthorize\ModuleOptions;
use SpiffyAuthorize\Provider\Identity\AuthenticationProvider;
use SpiffyAuthorize\Factory\CollectorPermissionFactory;
use SpiffyAuthorize\Service\RbacService;
use SpiffyAuthorizeTest\Asset\Identity;
use SpiffyAuthorizeTest\Asset\RbacRoleProvider;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;

class CollectorPermissionFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceReturned()
    {
        $identityProvider = new AuthenticationProvider();
        $identityProvider->getAuthService()->getStorage()->write(new Identity());

        $service = new RbacService();
        $service->setIdentityProvider($identityProvider);
        $service->getEventManager()->attach(new RbacRoleProvider());

        $options = new ModuleOptions();
        $options->setAuthorizeService('AuthorizeService');

        $sm = m::mock('Zend\ServiceManager\ServiceManager');
        $sm->shouldReceive('get')->with('AuthorizeService')->andReturn($service);
        $sm->shouldReceive('get')->with('SpiffyAuthorize\ModuleOptions')->andReturn($options);

        $factory  = new CollectorPermissionFactory();
        $instance = $factory->createService($sm);
        $instance->collect(new MvcEvent());

        $permissions = $instance->getPermissions();

        $this->assertInstanceOf('SpiffyAuthorize\Collector\PermissionCollector', $instance);
        $this->assertCount(1, $permissions);
        $this->assertArrayHasKey('role1', $permissions);
        $this->assertCount(3, $permissions['role1']);
    }
}
