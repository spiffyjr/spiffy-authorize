<?php

namespace SpiffyAuthorizeTest\Provider\Identity;

use SpiffyAuthorize\Provider\Identity\ZendAuthentication;
use SpiffyAuthorizeTest\Asset\EmptyIdentity;
use SpiffyAuthorizeTest\Asset\IdentityAclRole;
use SpiffyAuthorizeTest\Asset\IdentityObjectRole;
use SpiffyAuthorizeTest\Asset\IdentityRbacRole;
use Zend\Authentication\AuthenticationService;

class ZendAuthenticationTest extends \PHPUnit_Framework_TestCase
{
    public function testNoIdentityReturnsDefaultUnauthorizedRole()
    {
        $service  = new AuthenticationService();
        $provider = new ZendAuthentication($service);

        $this->assertEquals([$provider->getDefaultUnauthorizedRole()], $provider->getIdentityRoles());
    }

    public function testIdentityWithNoRolesReturnsDefaultAuthorizedRole()
    {
        $service  = new AuthenticationService();
        $provider = new ZendAuthentication($service);

        $service->getStorage()->write([]);
        $this->assertEquals([$provider->getDefaultAuthorizedRole()], $provider->getIdentityRoles());

        $service->getStorage()->write(new EmptyIdentity());
        $this->assertEquals([$provider->getDefaultAuthorizedRole()], $provider->getIdentityRoles());
    }

    public function testRbacRolesConvertedToString()
    {
        $service  = new AuthenticationService();
        $service->getStorage()->write(new IdentityRbacRole());

        $provider = new ZendAuthentication($service);
        $result   = $provider->getIdentityRoles();

        $this->assertEquals(['foo','bar'], $result);
    }

    public function testAclRolesConvertedToString()
    {
        $service  = new AuthenticationService();
        $service->getStorage()->write(new IdentityAclRole());

        $provider = new ZendAuthentication($service);
        $result   = $provider->getIdentityRoles();

        $this->assertEquals(['foo','bar'], $result);
    }

    public function testObjectsConvertedToString()
    {
        $service  = new AuthenticationService();
        $service->getStorage()->write(new IdentityObjectRole());

        $provider = new ZendAuthentication($service);
        $result   = $provider->getIdentityRoles();

        $this->assertEquals(['foo','bar'], $result);
    }
}