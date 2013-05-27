<?php

namespace SpiffyAuthorizeTest\Service;

use SpiffyAuthorize\Service\ProviderRoleFactory;
use SpiffyTest\Framework\TestCase;

class ProviderRoleFactoryTest extends TestCase
{
    public function testProvidersReturned()
    {
        $factory   = new ProviderRoleFactory();
        $providers = $factory->createService($this->getServiceManager());

        $this->assertCount(1, $providers);
        $this->assertEquals(['admin' => ['moderator']], $providers[0]->getRules());
    }
}