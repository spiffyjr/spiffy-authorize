<?php

namespace SpiffyAuthorizeTest\Provider\Permission\Config;

use SpiffyAuthorize\Provider\Permission\Config\RbacProvider;
use SpiffyAuthorize\Service\RbacService;
use SpiffyAuthorizeTest\Asset\RbacRoleProvider;

class RbacProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testConfig()
    {
        $config = new RbacProvider();
        $config->setRules(array(
            'foo' => 'role1',
            'bar' => array('child1', 'subchild2')
        ));

        $service = new RbacService();
        $service->getEventManager()->attach(new RbacRoleProvider());
        $service->getEventManager()->attach($config);

        $container = $service->getContainer();

        $this->assertTrue($container->getRole('role1')->hasPermission('foo'));
        $this->assertTrue($container->getRole('child1')->hasPermission('bar'));
        $this->assertTrue($container->getRole('subchild2')->hasPermission('bar'));
    }
}