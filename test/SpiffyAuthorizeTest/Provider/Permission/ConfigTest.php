<?php

namespace SpiffyAuthorizeTest\Provider\Permission;

use SpiffyAuthorize\Provider\Permission\Config;
use SpiffyAuthorize\Service\Rbac;
use SpiffyAuthorizeTest\Asset\RbacRoleProvider;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testConfig()
    {
        $config = new Config([
            'foo' => 'role1',
            'bar' => ['child1', 'subchild2']
        ]);

        $service = new Rbac();
        $service->getEventManager()->attach(new RbacRoleProvider());
        $service->getEventManager()->attach($config);

        $container = $service->getContainer();

        $this->assertTrue($container->getRole('role1')->hasPermission('foo'));
        $this->assertTrue($container->getRole('child1')->hasPermission('bar'));
        $this->assertTrue($container->getRole('subchild2')->hasPermission('bar'));
    }
}