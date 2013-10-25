<?php

namespace SpiffyAuthorizeTest\Factory;

use SpiffyAuthorize\Factory\ProviderRoleFactory;
use SpiffyTest\Framework\TestCase;

class ProviderRoleFactoryTest extends TestCase
{
    public function testProvidersReturned()
    {
        $factory   = new ProviderRoleFactory();
        $providers = $factory->createService($this->getServiceManager());

        $this->assertCount(1, $providers);
        $this->assertEquals(array('admin' => array('moderator')), $providers[0]->getRules());
    }
}
