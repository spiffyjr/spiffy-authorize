<?php

namespace SpiffyAuthorizeTest\Provider\Identity;

use SpiffyAuthorize\Provider\Identity\AuthenticationProvider;
use SpiffyAuthorizeTest\Asset\EmptyIdentity;
use SpiffyAuthorizeTest\Asset\IdentityAclRole;
use SpiffyAuthorizeTest\Asset\IdentityObjectRole;
use SpiffyAuthorizeTest\Asset\IdentityRbacRole;
use Zend\Authentication\AuthenticationService;

class AuthenticationProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testNoIdentityReturnsDefaultUnauthorizedRole()
    {
        $service  = new AuthenticationService();
        $provider = new AuthenticationProvider($service);

        $this->assertEquals([$provider->getDefaultUnauthorizedRole()], $provider->getIdentityRoles());
    }

    public function testIdentityWithNoRolesReturnsDefaultAuthorizedRole()
    {
        $service  = new AuthenticationService();
        $provider = new AuthenticationProvider($service);

        $service->getStorage()->write([]);
        $this->assertEquals([$provider->getDefaultAuthorizedRole()], $provider->getIdentityRoles());

        $service->getStorage()->write(new EmptyIdentity());
        $this->assertEquals([$provider->getDefaultAuthorizedRole()], $provider->getIdentityRoles());
    }

    public function testRbacRolesConvertedToString()
    {
        $service  = new AuthenticationService();
        $service->getStorage()->write(new IdentityRbacRole());

        $provider = new AuthenticationProvider($service);
        $result   = $provider->getIdentityRoles();

        $this->assertEquals(['foo','bar'], $result);
    }

    public function testAclRolesConvertedToString()
    {
        $service  = new AuthenticationService();
        $service->getStorage()->write(new IdentityAclRole());

        $provider = new AuthenticationProvider($service);
        $result   = $provider->getIdentityRoles();

        $this->assertEquals(['foo','bar'], $result);
    }

    public function testObjectsConvertedToString()
    {
        $service  = new AuthenticationService();
        $service->getStorage()->write(new IdentityObjectRole());

        $provider = new AuthenticationProvider($service);
        $result   = $provider->getIdentityRoles();

        $this->assertEquals(['foo','bar'], $result);
    }
}