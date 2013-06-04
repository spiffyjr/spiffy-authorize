<?php

namespace SpiffyAuthorizeTest\Service;

use SpiffyAuthorize\Service\ProviderPermissionFactory;
use SpiffyTest\Framework\TestCase;

class ProviderPermissionFactoryTest extends TestCase
{
    public function testProvidersReturned()
    {
        $factory   = new ProviderPermissionFactory();
        $providers = $factory->createService($this->getServiceManager());

        $this->assertCount(1, $providers);
        $this->assertEquals(array('foo' => array('bar', 'baz')), $providers[0]->getRules());
    }
}