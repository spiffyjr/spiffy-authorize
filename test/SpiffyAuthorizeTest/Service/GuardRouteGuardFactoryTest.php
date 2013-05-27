<?php

namespace SpiffyAuthorizeTest\Service;

use Mockery as m;
use SpiffyAuthorize\Options\ModuleOptions;
use SpiffyAuthorize\Service\GuardRouteGuardFactory;
use SpiffyAuthorizeTest\Asset\AuthorizeService;
use Zend\ServiceManager\ServiceManager;

class GuardRouteGuardFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceReturned()
    {
        $options = new ModuleOptions();
        $options->setAuthorizeService('AuthorizeService');

        $sm = m::mock('Zend\ServiceManager\ServiceManager');
        $sm->shouldReceive('get')->with('AuthorizeService')->andReturn(new AuthorizeService());
        $sm->shouldReceive('get')->with('SpiffyAuthorize\Options\ModuleOptions')->andReturn($options);

        $factory  = new GuardRouteGuardFactory();
        $instance = $factory->createService($sm);

        $this->assertInstanceOf('SpiffyAuthorize\Guard\RouteGuard', $instance);
    }
}