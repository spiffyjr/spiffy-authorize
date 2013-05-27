<?php

namespace SpiffyAuthorizeTest\Service;

use Mockery as m;
use SpiffyAuthorize\Options\ModuleOptions;
use SpiffyAuthorize\Service\ViewStrategyFactory;
use SpiffyAuthorize\View\Strategy\UnauthorizedStrategy;

class ViewStrategyFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceReturned()
    {
        $options = new ModuleOptions();
        $options->setViewStrategy('Strategy');

        $sm = m::mock('Zend\ServiceManager\ServiceManager');
        $sm->shouldReceive('get')->with('SpiffyAuthorize\Options\ModuleOptions')->andReturn($options);
        $sm->shouldReceive('get')->with('Strategy')->andReturn(new UnauthorizedStrategy());

        $factory  = new ViewStrategyFactory();
        $instance = $factory->createService($sm);

        $this->assertInstanceOf('SpiffyAuthorize\View\Strategy\UnauthorizedStrategy', $instance);
    }
}