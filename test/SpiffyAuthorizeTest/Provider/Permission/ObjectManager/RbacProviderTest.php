<?php

namespace SpiffyAuthorizeTest\Provider\Permission\ObjectManager;

use Mockery as m;
use SpiffyAuthorize\Provider\Permission\ObjectManager\RbacProvider;
use SpiffyAuthorize\Service\RbacService;
use SpiffyAuthorizeTest\Asset;

class RbacProviderTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testExceptionThrownWhenMissingObjectManager()
    {
        $permissions = new RbacProvider();

        $this->setExpectedException(
            'SpiffyAuthorize\Provider\Permission\Exception\RuntimeException',
            'No object_manager was set.'
        );

        $service = new RbacService();
        $service->getEventManager()->attach($permissions);
        $service->getContainer();
    }

    public function testExceptionThrownWhenMissingTargetClass()
    {
        $om = m::mock('Doctrine\ORM\EntityManager');
        $om->shouldReceive('getRepository')->andReturn(m::mock('Doctrine\ORM\EntityRepository'));

        $permissions = new RbacProvider();
        $permissions->setObjectManager($om);

        $this->setExpectedException(
            'SpiffyAuthorize\Provider\Permission\Exception\RuntimeException',
            'No target_class was set.'
        );

        $service = new RbacService();
        $service->getEventManager()->attach($permissions);
        $service->getContainer();
    }

    public function testPermissionInterfacePermissions()
    {
        $result = array(
            new Asset\Permission('foo', array('role1', 'subchild2')),
            new Asset\Permission('bar', array('child2'))
        );

        $or = m::mock('Doctrine\ORM\EntityRepository');
        $or->shouldReceive('findAll')->andReturn($result);

        $om = m::mock('Doctrine\ORM\EntityManager');
        $om->shouldReceive('getRepository')->andReturn($or);

        $permissions = new RbacProvider();
        $permissions->setObjectManager($om);
        $permissions->setTargetClass('Entity');
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
        $result = array(
            array( 'foo' => array('role1', 'subchild2') ),
            array( 'bar' => array('child2') ),
            array( 'baz' => 'child2' )
        );

        $or = m::mock('Doctrine\ORM\EntityRepository');
        $or->shouldReceive('findAll')->andReturn($result);

        $om = m::mock('Doctrine\ORM\EntityManager');
        $om->shouldReceive('getRepository')->andReturn($or);

        $permissions = new RbacProvider();
        $permissions->setObjectManager($om);
        $permissions->setTargetClass('Entity');
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
        $result = array('foo');
        $roles  = new Asset\RbacRoleProvider();

        $or = m::mock('Doctrine\ORM\EntityRepository');
        $or->shouldReceive('findAll')->andReturn($result);

        $om = m::mock('Doctrine\ORM\EntityManager');
        $om->shouldReceive('getRepository')->andReturn($or);

        $permissions = new RbacProvider();
        $permissions->setObjectManager($om);
        $permissions->setTargetClass('Entity');

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
        $result = array( array( 'foo' ) );
        $roles  = new Asset\RbacRoleProvider();

        $or = m::mock('Doctrine\ORM\EntityRepository');
        $or->shouldReceive('findAll')->andReturn($result);

        $om = m::mock('Doctrine\ORM\EntityManager');
        $om->shouldReceive('getRepository')->andReturn($or);

        $permissions = new RbacProvider();
        $permissions->setObjectManager($om);
        $permissions->setTargetClass('Entity');

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