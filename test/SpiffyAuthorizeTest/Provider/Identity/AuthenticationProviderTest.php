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

        $this->assertEquals(array($provider->getDefaultGuestRole()), $provider->getIdentityRoles());
    }

    public function testAuthServiceIsLazyLoaded()
    {
        $provider = new AuthenticationProvider();
        $this->assertInstanceOf('Zend\Authentication\AuthenticationService', $provider->getAuthService());
    }

    public function testGetIdentityRolesUsesLazyLoaderAuthService()
    {
        $provider = new AuthenticationProvider();
        $provider->getIdentityRoles();
    }

    public function testIdentityWithNoRolesReturnsDefaultAuthorizedRole()
    {
        $service  = new AuthenticationService();
        $provider = new AuthenticationProvider();

        $service->getStorage()->write(array());
        $this->assertEquals(array($provider->getDefaultRole()), $provider->getIdentityRoles());

        $service->getStorage()->write(new EmptyIdentity());
        $this->assertEquals(array($provider->getDefaultRole()), $provider->getIdentityRoles());
    }

    public function testRbacRolesConvertedToString()
    {
        $service  = new AuthenticationService();
        $service->getStorage()->write(new IdentityRbacRole());

        $provider = new AuthenticationProvider();
        $provider->setAuthService($service);
        $result = $provider->getIdentityRoles();

        $this->assertEquals(array('foo','bar'), $result);
    }

    public function testAclRolesConvertedToString()
    {
        $service  = new AuthenticationService();
        $service->getStorage()->write(new IdentityAclRole());

        $provider = new AuthenticationProvider();
        $provider->setAuthService($service);
        $result = $provider->getIdentityRoles();

        $this->assertEquals(array('foo','bar'), $result);
    }

    public function testObjectsConvertedToString()
    {
        $service  = new AuthenticationService();
        $service->getStorage()->write(new IdentityObjectRole());

        $provider = new AuthenticationProvider();
        $provider->setAuthService($service);
        $result   = $provider->getIdentityRoles();

        $this->assertEquals(array('foo','bar'), $result);
    }
}