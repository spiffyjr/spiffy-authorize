<?php

namespace SpiffyAuthorizeTest\Factory;

use Mockery as m;
use SpiffyAuthorize\ModuleOptions;
use SpiffyAuthorize\Factory\ViewStrategyUnauthorizedFactory;
use Zend\ServiceManager\ServiceManager;

class ViewStrategyUnauthorizedFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceReturned()
    {
        $options = new ModuleOptions();

        $sm = m::mock('Zend\ServiceManager\ServiceManager');
        $sm->shouldReceive('get')->with('SpiffyAuthorize\ModuleOptions')->andReturn($options);

        $factory  = new ViewStrategyUnauthorizedFactory();
        $instance = $factory->createService($sm);

        $this->assertInstanceOf('SpiffyAuthorize\View\UnauthorizedStrategy', $instance);
        $this->assertEquals('error/403', $instance->getTemplate());

        $options->setViewTemplate('error-foo');

        $instance = $factory->createService($sm);

        $this->assertInstanceOf('SpiffyAuthorize\View\UnauthorizedStrategy', $instance);
        $this->assertEquals('error-foo', $instance->getTemplate());
    }
}
