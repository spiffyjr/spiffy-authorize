<?php

namespace SpiffyAuthorizeTest\Provider\Permission\ObjectRepository;

use Mockery as m;
use SpiffyAuthorize\Provider\Permission\ObjectRepository\RbacProvider;
use SpiffyAuthorize\Service\RbacService;
use SpiffyAuthorizeTest\Asset;

class RbacProviderTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testPermissionInterfacePermissions()
    {
        $result = [
            new Asset\Permission('foo', ['role1', 'subchild2']),
            new Asset\Permission('bar', ['child2'])
        ];

        $or = m::mock('Doctrine\Common\Persistence\ObjectRepository');
        $or->shouldReceive('findAll')->andReturn($result);

        $permissions = new RbacProvider();
        $permissions->setObjectRepository($or);
        $roles = new Asset\RbacRoleProvider();

        $service = new RbacService();
        $service->getEventManager()->attach($roles);
        $service->getEventManager()->attach($permissions);

        $container = $service->getContainer();
        $this->assertTrue($container->getRole('role1')->hasPermission('foo'));
        $this->assertTrue($container->getRole('subchild2')->hasPermission('foo'));
        $this->assertTrue($container->getRole('child2')->hasPermission('bar'));
    }

    public function testArrayPermissions()
    {
        $result = [
            [ 'foo' => ['role1', 'subchild2'] ],
            [ 'bar' => ['child2'] ],
            [ 'baz' => 'child2' ]
        ];

        $or = m::mock('Doctrine\Common\Persistence\ObjectRepository');
        $or->shouldReceive('findAll')->andReturn($result);

        $permissions = new RbacProvider();
        $permissions->setObjectRepository($or);
        $roles = new Asset\RbacRoleProvider();

        $service = new RbacService();
        $service->getEventManager()->attach($roles);
        $service->getEventManager()->attach($permissions);

        $container = $service->getContainer();
        $this->assertTrue($container->getRole('role1')->hasPermission('foo'));
        $this->assertTrue($container->getRole('subchild2')->hasPermission('foo'));
        $this->assertTrue($container->getRole('child2')->hasPermission('bar'));
        $this->assertTrue($container->getRole('child2')->hasPermission('baz'));
    }

    public function testStringPermissionsException()
    {
        $result = ['foo'];
        $roles  = new Asset\RbacRoleProvider();

        $or = m::mock('Doctrine\Common\Persistence\ObjectRepository');
        $or->shouldReceive('findAll')->andReturn($result);

        $permissions = new RbacProvider();
        $permissions->setObjectRepository($or);

        $service = new RbacService();
        $service->getEventManager()->attach($roles);
        $service->getEventManager()->attach($permissions);

        $this->setExpectedException(
            'SpiffyAuthorize\Provider\Permission\Exception\InvalidArgumentException',
            'unknown permission entity type'
        );

        $service->getContainer();
    }

    public function testArrayPermissionsException()
    {
        $result = [ [ 'foo' ] ];
        $roles  = new Asset\RbacRoleProvider();

        $or = m::mock('Doctrine\Common\Persistence\ObjectRepository');
        $or->shouldReceive('findAll')->andReturn($result);

        $permissions = new RbacProvider();
        $permissions->setObjectRepository($or);

        $service = new RbacService();
        $service->getEventManager()->attach($roles);
        $service->getEventManager()->attach($permissions);

        $this->setExpectedException(
            'SpiffyAuthorize\Provider\Permission\Exception\InvalidArgumentException',
            'roles provided with no permission name'
        );

        $service->getContainer();
    }
}