<?php

namespace SpiffyAuthorizeTest\Provider\Identity;

use SpiffyAuthorize\Provider\Identity\AuthenticationProvider;
use SpiffyAuthorizeTest\Asset\EmptyIdentity;
use SpiffyAuthorizeTest\Asset\IdentityAclRole;
use SpiffyAuthorizeTest\Asset\IdentityObjectRole;
use SpiffyAuthorizeTest\Asset\IdentityRbacRole;
use Zend\Authentication\AuthenticationService;

class IdentityProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testNoIdentityReturnsDefaultUnauthorizedRole()
    {
        $provider = new AuthenticationProvider(new AuthenticationService());

        $this->assertEquals(array($provider->getDefaultGuestRole()), $provider->getIdentityRoles());
    }

    public function testGetIdentityRolesUsesLazyLoaderAuthService()
    {
        $provider = new AuthenticationProvider(new AuthenticationService());;
        $provider->getIdentityRoles();
    }

    public function testIdentityWithNoRolesReturnsDefaultAuthorizedRole()
    {
        $service  = new AuthenticationService();
        $provider = new AuthenticationProvider($service);

        $service->getStorage()->write(array());
        $this->assertEquals(array($provider->getDefaultRole()), $provider->getIdentityRoles());

        $service->getStorage()->write(new EmptyIdentity());
        $this->assertEquals(array($provider->getDefaultRole()), $provider->getIdentityRoles());
    }

    public function testRbacRolesConvertedToString()
    {
        $service  = new AuthenticationService();
        $service->getStorage()->write(new IdentityRbacRole());

        $provider = new AuthenticationProvider($service);
        $result = $provider->getIdentityRoles();

        $this->assertEquals(array('foo','bar'), $result);
    }

    public function testAclRolesConvertedToString()
    {
        $service  = new AuthenticationService();
        $service->getStorage()->write(new IdentityAclRole());

        $provider = new AuthenticationProvider($service);
        $result = $provider->getIdentityRoles();

        $this->assertEquals(array('foo','bar'), $result);
    }

    public function testObjectsConvertedToString()
    {
        $service  = new AuthenticationService();
        $service->getStorage()->write(new IdentityObjectRole());

        $provider = new AuthenticationProvider($service);
        $result   = $provider->getIdentityRoles();

        $this->assertEquals(array('foo','bar'), $result);
    }

    public function testOriginalRolesAreNotModifiedOnCollection()
    {
        $identity = new IdentityObjectRole();
        /** @var \ArrayObject $original */
        $original = $identity->getRoles();
        $original = clone $original;

        $service = new AuthenticationService();
        $service->getStorage()->write($identity);

        $provider = new AuthenticationProvider($service);
        $provider->getIdentityRoles();

        $this->assertEquals($original, $identity->getRoles());
    }
}