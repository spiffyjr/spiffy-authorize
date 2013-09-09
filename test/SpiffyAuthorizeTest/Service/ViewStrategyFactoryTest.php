<?php

namespace SpiffyAuthorizeTest\Service;

use Mockery as m;
use SpiffyAuthorize\ModuleOptions;
use SpiffyAuthorize\Service\ViewStrategyFactory;
use SpiffyAuthorize\View\UnauthorizedStrategy;

class ViewStrategyFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceReturned()
    {
        $options = new ModuleOptions();
        $options->setViewStrategy('Strategy');

        $sm = m::mock('Zend\ServiceManager\ServiceManager');
        $sm->shouldReceive('get')->with('SpiffyAuthorize\ModuleOptions')->andReturn($options);
        $sm->shouldReceive('get')->with('Strategy')->andReturn(new UnauthorizedStrategy());

        $factory  = new ViewStrategyFactory();
        $instance = $factory->createService($sm);

        $this->assertInstanceOf('SpiffyAuthorize\View\UnauthorizedStrategy', $instance);
    }
}