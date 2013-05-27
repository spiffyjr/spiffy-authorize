<?php

namespace SpiffyAuthorizeTest\Collector;

use SpiffyAuthorize\Collector\RoleCollector;
use SpiffyAuthorize\Provider\Identity\AuthenticationProvider;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\MvcEvent;

class RbacCollectorTest extends \PHPUnit_Framework_TestCase
{
    public function testSerializes()
    {
        $collector = $this->getCollector();

        $this->assertEquals(serialize(['guest']), $collector->serialize());

        $collector->unserialize(serialize(['guest']));

        $this->assertEquals(['guest'], $collector->getRoles());
    }

    public function testCollectedRoles()
    {
        $this->assertEquals(['guest'], $this->getCollector()->getRoles());
    }

    /**
     * @return RoleCollector
     */
    protected function getCollector()
    {
        $provider  = new AuthenticationProvider();
        $provider->setAuthService(new AuthenticationService());

        $collector = new RoleCollector($provider);
        $collector->collect(new MvcEvent());

        return $collector;
    }
}