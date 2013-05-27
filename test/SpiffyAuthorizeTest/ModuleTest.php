<?php

namespace SpiffyAuthorizeTest;

use Mockery as m;
use SpiffyAuthorize\Guard\RouteGuard;
use SpiffyAuthorize\Module;
use SpiffyTest\Framework\TestCase;
use Zend\EventManager\EventManager;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;

class ModuleTest extends TestCase
{
    public function testGuardsAreRegistered()
    {
        $em = new EventManager();
        $sm = $this->getServiceManager();

        $app = m::mock('Zend\Mvc\Application');
        $app->shouldReceive('getEventManager')
            ->andReturn($em);
        $app->shouldReceive('getServiceManager')
            ->andReturn($sm);

        $mvcEvent = new MvcEvent();
        $mvcEvent->setTarget($app);

        $module = new Module();
        $module->onBootstrap($mvcEvent);

        $this->assertCount(2, $em->getListeners(MvcEvent::EVENT_ROUTE));
    }
}