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
    public function testGuards()
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

        // From module.config.php merged with test.module.config.php
        $this->assertCount(3, $em->getListeners(MvcEvent::EVENT_ROUTE));
    }

    public function testStrategy()
    {

    }
}