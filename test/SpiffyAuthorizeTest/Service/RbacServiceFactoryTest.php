<?php

namespace SpiffyAuthorizeTest\Service;

use SpiffyAuthorize\AuthorizeEvent;
use SpiffyAuthorize\Options\ModuleOptions;
use SpiffyAuthorize\Service\RbacServiceFactory;
use SpiffyAuthorizeTest\Asset\Identity;
use SpiffyTest\Framework\TestCase;

class RbacServiceFactoryTest extends TestCase
{
    public function testInstanceReturned()
    {
        $options = new ModuleOptions();
        $options->setIdentityProvider('IdentityProvider');

        $sm = $this->getServiceManager();
        $sm->setService('IdentityProvider', new Identity());

        $factory = new RbacServiceFactory();

        /** @var \SpiffyAuthorize\Service\RbacService $instance */
        $instance = $factory->createService($sm);

        $this->assertInstanceOf('SpiffyAuthorize\Provider\Identity\AuthenticationProvider', $instance->getIdentityProvider());
        $this->assertInstanceOf('SpiffyAuthorize\Service\RbacService', $instance);
        $this->assertCount(2, $instance->getEventManager()->getListeners(AuthorizeEvent::EVENT_INIT));
    }
}