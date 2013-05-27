<?php

namespace SpiffyAuthorizeTest\Service;

use SpiffyAuthorize\Provider\Identity\AuthenticationProvider;
use SpiffyAuthorize\Service\RbacService;
use SpiffyAuthorizeTest\Asset\SimpleAssertion;
use SpiffyAuthorizeTest\Asset\Identity;
use SpiffyAuthorizeTest\Asset\RbacRoleProvider;
use Zend\Authentication\AuthenticationService;

class RbacServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AuthenticationProvider
     */
    protected $identityProvider;

    public function setUp()
    {
        $authService  = new AuthenticationService();
        $authService->getStorage()->write(new Identity());

        $this->identityProvider = new AuthenticationProvider();
        $this->identityProvider->setAuthService($authService);
    }

    public function testLoadingRolesFromInitProvider()
    {
        $rbac     = new RbacService();
        $provider = new RbacRoleProvider();
        $rbac->getEventManager()->attach($provider);

        $this->assertCount(2, $rbac->getContainer()->getChildren());
        $this->assertEquals(true, $rbac->getContainer()->hasRole('role1'));
    }

    public function testIsAuthorized()
    {
        $rbac     = new RbacService();
        $provider = new RbacRoleProvider();
        $rbac->getEventManager()->attach($provider);
        $this->assertFalse($rbac->isAuthorized('foo'));

        $rbac->setIdentityProvider($this->identityProvider);
        $this->assertFalse($rbac->isAuthorized('foo'));
        $this->assertTrue($rbac->isAuthorized('role1'));
        $this->assertTrue($rbac->isAuthorized('child2'));
        $this->assertTrue($rbac->isAuthorized('subchild1'));
        $this->assertFalse($rbac->isAuthorized('foo'));
    }

    public function testIsAuthorizedAssertions()
    {
        $rbac     = new RbacService();
        $provider = new RbacRoleProvider();
        $rbac->getEventManager()->attach($provider);
        $rbac->setIdentityProvider($this->identityProvider);

        $rbac->registerAssertion('role1', function() { return true; });
        $this->assertTrue($rbac->isAuthorized('role1'));
        $this->assertFalse($rbac->isAuthorized('role1', function() { return false; }));
    }

    public function testHasRole()
    {
        $rbac = new RbacService();
        $rbac->getEventManager()->attach(new RbacRoleProvider());
        $this->assertFalse($rbac->hasRole('role1'));
        $this->assertFalse($rbac->hasRole('role2'));

        $rbac->setIdentityProvider($this->identityProvider);
        $this->assertTrue($rbac->hasRole('role1'));
        $this->assertFalse($rbac->hasRole('role2'));
        $this->assertTrue($rbac->hasRole('child1'));
        $this->assertTrue($rbac->hasRole('child2'));
        $this->assertFalse($rbac->hasRole('child3'));

        $this->assertTrue($rbac->hasRole('subchild1'));
        $this->assertFalse($rbac->hasRole('foo'));
    }

    public function testHasResource()
    {
        $rbac = new RbacService();
        $rbac->getEventManager()->attach(new RbacRoleProvider());
        $this->assertFalse($rbac->hasResource('role1'));

        $rbac->setIdentityProvider($this->identityProvider);
        $this->assertTrue($rbac->hasResource('role1'));
        $this->assertTrue($rbac->hasResource('child2'));
        $this->assertTrue($rbac->hasResource('subchild1'));
        $this->assertFalse($rbac->hasResource('foo'));
    }

    public function testRegisterAssertion()
    {
        $rbac = new RbacService();
        $rbac->registerAssertion('foo.bar', function() {});
        $this->assertEquals(['foo.bar' => function() {}], $rbac->getAssertions());

        $rbac->registerAssertion('foo.bar', new SimpleAssertion());
        $this->assertEquals(['foo.bar' => new SimpleAssertion()], $rbac->getAssertions());

        $this->setExpectedException('SpiffyAuthorize\Assertion\Exception\InvalidArgumentException');

        $rbac->registerAssertion('foo.bar', false);
    }

    public function testClearAssertions()
    {
        $assertions = [
            'foo' => function() {},
            'bar' => new SimpleAssertion()
        ];

        $rbac = new RbacService();
        $rbac->setAssertions($assertions);

        $this->assertCount(2, $rbac->getAssertions());

        $rbac->clearAssertions();
        $this->assertCount(0, $rbac->getAssertions());
    }

    public function testSettingAssertions()
    {
        $assertions = [
            'foo' => function() {},
            'bar' => new SimpleAssertion()
        ];

        $rbac = new RbacService();
        $rbac->setAssertions($assertions);
        $this->assertCount(2, $rbac->getAssertions());

        $this->setExpectedException('SpiffyAuthorize\Assertion\Exception\InvalidArgumentException');
        $rbac->setAssertions([
            'foo' => false
        ]);
    }
}