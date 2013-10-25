<?php

namespace SpiffyAuthorizeTest\Factory;

use Mockery as m;
use SpiffyAuthorize\ModuleOptions;
use SpiffyAuthorize\Factory\GuardRouteFactory;
use SpiffyAuthorizeTest\Asset\AuthorizeService;
use Zend\ServiceManager\ServiceManager;

class GuardRouteFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceReturned()
    {
        $options = new ModuleOptions();
        $options->setAuthorizeService('AuthorizeService');

        $sm = m::mock('Zend\ServiceManager\ServiceManager');
        $sm->shouldReceive('get')->with('AuthorizeService')->andReturn(new AuthorizeService());
        $sm->shouldReceive('get')->with('SpiffyAuthorize\ModuleOptions')->andReturn($options);

        $factory  = new GuardRouteFactory();
        $instance = $factory->createService($sm);

        $this->assertInstanceOf('SpiffyAuthorize\Guard\RouteGuard', $instance);
    }
}
