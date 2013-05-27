<?php

namespace SpiffyAuthorizeTest\Service;

use SpiffyAuthorize\AuthorizeEvent;
use SpiffyAuthorize\Service\RbacServiceFactory;
use SpiffyTest\Framework\TestCase;

class RbacServiceFactoryTest extends TestCase
{
    public function testInstanceReturned()
    {
        $sm       = $this->getServiceManager();
        $factory  = new RbacServiceFactory();

        /** @var \SpiffyAuthorize\Service\RbacService $instance */
        $instance = $factory->createService($sm);

        $this->assertInstanceOf('SpiffyAuthorize\Service\RbacService', $instance);
        $this->assertCount(2, $instance->getEventManager()->getListeners(AuthorizeEvent::EVENT_INIT));
    }
}