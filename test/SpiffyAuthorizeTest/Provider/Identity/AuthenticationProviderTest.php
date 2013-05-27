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
        $provider = new AuthenticationProvider();
        $provider->setAuthService(new AuthenticationService());

        $this->assertEquals([$provider->getDefaultGuestRole()], $provider->getIdentityRoles());
    }

    public function testIdentityWithNoRolesReturnsDefaultAuthorizedRole()
    {
        $service  = new AuthenticationService();
        $provider = new AuthenticationProvider();
        $provider->setAuthService($service);

        $service->getStorage()->write([]);
        $this->assertEquals([$provider->getDefaultRole()], $provider->getIdentityRoles());

        $service->getStorage()->write(new EmptyIdentity());
        $this->assertEquals([$provider->getDefaultRole()], $provider->getIdentityRoles());
    }

    public function testRbacRolesConvertedToString()
    {
        $service  = new AuthenticationService();
        $service->getStorage()->write(new IdentityRbacRole());

        $provider = new AuthenticationProvider();
        $provider->setAuthService($service);
        $result = $provider->getIdentityRoles();

        $this->assertEquals(['foo','bar'], $result);
    }

    public function testAclRolesConvertedToString()
    {
        $service  = new AuthenticationService();
        $service->getStorage()->write(new IdentityAclRole());

        $provider = new AuthenticationProvider();
        $provider->setAuthService($service);
        $result = $provider->getIdentityRoles();

        $this->assertEquals(['foo','bar'], $result);
    }

    public function testObjectsConvertedToString()
    {
        $service  = new AuthenticationService();
        $service->getStorage()->write(new IdentityObjectRole());

        $provider = new AuthenticationProvider();
        $provider->setAuthService($service);
        $result   = $provider->getIdentityRoles();

        $this->assertEquals(['foo','bar'], $result);
    }
}