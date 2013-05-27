<?php

namespace SpiffyAuthorizeTest\Service;

use Mockery as m;
use SpiffyAuthorize\Service\ProviderIdentityFactory;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\ServiceManager;

class ProviderIdentityFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceReturned()
    {
        $sm = m::mock('Zend\ServiceManager\ServiceManager');
        $sm->shouldReceive('get')->with('Zend\Authentication\AuthenticationService')->andReturn(new AuthenticationService());

        $factory  = new ProviderIdentityFactory();
        $instance = $factory->createService($sm);

        $this->assertInstanceOf('SpiffyAuthorize\Provider\Identity\AuthenticationProvider', $instance);
    }
}